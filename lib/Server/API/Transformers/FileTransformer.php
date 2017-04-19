<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\File;

class FileTransformer extends BaseTransformer
{
    public function transform(File $file)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($file), [
            'name'         => $file->name,
            'fileName'     => $file->file_name,
            'mimeType'     => $file->mime_type,
            'size'         => (int) $file->size,
            'sha1Checksum' => $file->sha1_checksum,
            'text'         => $file->text,

            // TODO: make this configurable before providing the server as loadable module
            'accessUrl'    => route('dummyfile.show', $file->file_name),
            'downloadUrl'  => route('dummyfile.serve', $file->file_name),

            'externalServiceUrl' => $file->external_service_url,
            'masterFile'         => ($file->masterFile) ? route('api.v1.file.show', $file->masterFile) : null,
            'derivativeFile'     => $this->collectionRouteList('api.v1.file.show', $file->derivativeFiles),

            'license'     => $file->license,
            'fileLicense' => $file->file_license,
            'meeting'     => ($file->meeting) ? route('api.v1.meeting.show', $file->meeting) : null,
            'agendaItem'  => ($file->agenda_item) ? route('api.v1.agendaitem.show', $file->agendaItem) : null,
            'paper'       => ($file->paper) ? route('api.v1.paper.show', $file->paper) : null,
        ]);

        return $this->cleanupData($data, $file);
    }
}

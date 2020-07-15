<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropDemoserver extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'oparl_agenda_items',
            'oparl_bodies',
            'oparl_consultations',
            'oparl_consultations_organizations',
            'oparl_files',
            'oparl_keywords',
            'oparl_keywords_agenda_items',
            'oparl_keywords_bodies',
            'oparl_keywords_consultations',
            'oparl_keywords_files',
            'oparl_keywords_legislative_terms',
            'oparl_keywords_locations',
            'oparl_keywords_meetings',
            'oparl_keywords_memberships',
            'oparl_keywords_organizations',
            'oparl_keywords_papers',
            'oparl_keywords_people',
            'oparl_legislative_terms',
            'oparl_locations',
            'oparl_meetings',
            'oparl_meetings_auxiliary_files',
            'oparl_meetings_organizations',
            'oparl_meetings_participants',
            'oparl_memberships',
            'oparl_organizations',
            'oparl_papers',
            'oparl_papers_auxiliary_files',
            'oparl_papers_locations',
            'oparl_papers_originator_organizations',
            'oparl_papers_originator_people',
            'oparl_papers_related_papers',
            'oparl_papers_subordinated_papers',
            'oparl_papers_superordinated_papers',
            'oparl_papers_under_direction_of_organizations',
            'oparl_people',
            'oparl_systems',
        ];

        foreach ($tables as $table) {
            Schema::drop($table);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        throw new Exception('As there\'s a lot of accepted data loss during up(), down() is not provided.');
    }
}

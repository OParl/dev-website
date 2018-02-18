<?php

return array(
    'contact.title'       => 'Kontakt',
    'contact.info'        => 'Zur Kontaktaufnahme mit den OParl-Entwicklern gibt es verschiedene Möglichkeiten:',
    'contact.github'      => 'Die Diskussion über die Weiterentwicklung von OParl und das Melden von Fehlern von OParl-Produkten findet in den Projekten unserer GitHub-Organisation statt.',
    'contact.mailinglist' => 'OParl-Tech ist die Mailingliste auf der technische Neuheiten und Anfragen besprochen werden, die keinen Platz auf GitHub haben.',
    'contact.form_info'   => 'Kontaktformular',
    'contact.form'        => 'Für allgemeine Anfragen oder Anfragen, die Ihre gewerbliche Nutzung von OParl betreffen nutzen Sie bitte unser Kontaktformular.',

    'developers.title' => 'Übersicht',

    'developers.about-oparl.title' => 'Was ist OParl?',
    'developers.about-oparl.text'  => 'OParl ist ein seit Juni 2016 veröffentlichter Standard zur maschinenlesbaren Ausgabe von Daten aus sogenannten parlamentarischen Informationssystemen.',
    'developers.about-dev.title'   => 'Was finde ich hier?',
    'developers.about-dev.text'    => 'Das OParl-Entwicklerportal ist die zentrale Anlaufstelle zwischen OParl-Implementierern, OParl-Bereitstellern und OParl-Nutzern. Hier finden Sie technische Informationen zur Schnittstelle und weiterführendes Material zu deren Einsatz und Verwendbarkeit für verschiedene Zwecke.',

    'developers.demo'           => 'Einen OParl-Endpunkt der vollständig aus generierten Daten gespeist wird und somit ideal zum Testen und Demonstrieren Ihrer Anwendung geeignet ist',
    'developers.liboparl'       => 'liboparl - Die Standardbibliothek, um Anwendungen auf Basis von OParl-konformen Systemen zu entwickeln',
    'developers.implementors'   => 'Eine Liste von aktuellen OParl-Implementieren und Informationen zu Herstellern OParl-konformer Systeme',
    'developers.usage-examples' => 'Kleine Beispiele zum alltäglichen Nutzwert von OParl',

    'developers.validator.title'     => 'Validator',
    'developers.validator.info-text' => 'Hier können Sie Ihren OParl-Endpunkt validieren und ausführliche Informationen über die Konformität der genutzten Implementierung und über die Konsistenz der bereit gestellten Daten erhalten.',

    'demo.title' => 'API Demo',

    'downloads.title'                      => 'Downloads',
    'downloads.liboparl.title'             => 'LibOParl',
    'downloads.liboparl.description'       => 'LibOParl kann sowohl als Quellcode direkt von GitHub bezogen werden, als auch in vorkompilierten Paketen für Debian/Linux und macOS.',
    'downloads.liboparl.format.macos_brew' => 'Für macOS gibt es zu dem die Möglichkeit, liboparl über Homebrew zu beziehen. Dazu muss der Tap `efrane/myformulae` hinzugefügt werden. Dann kann liboparl mit `brew install liboparl` installiert werden. Nähere Informationen zur Benutzung von Homebrew sind unter [brew.sh](https://brew.sh) verfügbar.',
    'downloads.liboparl.format.source_zip' => 'Quellcode als Zip-Datei',
    'downloads.liboparl.format.debian_zip' => 'Library für Debian/GNU Linux',
    'downloads.liboparl.format.macos_zip'  => 'Library für macOS',
    'downloads.liboparl.sourcecode'        => 'Quellcode',
    'downloads.liboparl.packages'          => 'Pakete',

    'endpoints.title' => 'OParl-Endpunkte',
    'endpoints.text'  => 'Zur Zeit sind uns die folgenden OParl-Endpunkte bekannt.',
    'endpoints.copy'  => 'Diesen Endpunkt in die Zwischenablage kopieren.',

    'specification.title' => 'Spezifikation',

    'specification.download.title'         => 'Spezifikation herunterladen',
    'specification.download.select.title'  => 'Bitte wählen Sie ein Downloadformat aus',
    'specification.download.formatinfo'    => 'Wir stellen die Downloads der Spezifikation in verschiedenen Formaten zur Verfügung. Bitte wählen Sie das von Ihnen gewünschte und die gesuchte Version aus um fortzufahren.',
    'specification.download.singlefile'    => 'Dateien',
    'specification.download.archives'      => 'Archive',
    'specification.download.archives-info' => 'Die Archive enthalten immer alle verfügbaren Ausgabeformate.',
    'specification.download.format.pdf'    => 'PDF',
    'specification.download.format.epub'   => 'ePub',
    'specification.download.format.html'   => 'HTML (Alleinstehend)',
    'specification.download.format.docx'   => 'Microsoft Word',
    'specification.download.format.odt'    => 'OpenOffice/LibreOffice',
    'specification.download.format.txt'    => 'Text',
    'specification.download.format.zip'    => 'Zip',
    'specification.download.format.targz'  => 'Gzip',
    'specification.download.format.tarbz2' => 'Bzip2',

    'footer.disclaimer' => "macOS ist eine eingetragene Marke von Apple Inc, CA\n",

    'validation.explanation.duration'      => 'Die Natur der Validierung eines OParl-Endpunktes bedingt, dass jedes Element abgerufen und geprüft werden muss. Je nach Größe des Endpunktes kann dies unter Umständen einige Stunden in Anspruch nehmen. Falls Sie jedoch nach 24 Stunden noch keine Nachricht von bot@oparl.org erhalten haben sollten, möchten wir Sie bitten zu uns mit Angabe des von Ihnen beauftragten Endpunktes <a href=":contact_url">Kontakt</a> aufzunehmen.',
    'validation.completed'                 => '[OParl] Validierung für :endpoint abgeschlossen',
    'validation.form.email'                => 'E-Mail',
    'validation.form.email.description'    => 'Nach abgeschlossener Validierung erhalten Sie die Ergebnisse im ausgewählten Format an diese E-Mail-Adresse.',
    'validation.form.email.required'       => 'Sie müssen eine E-Mail-Adresse angeben, damit die Validierung durchgeführt wird.',
    'validation.form.email.invalid'        => 'Die angegebene E-Mail-Adresse ist ungültig.',
    'validation.form.endpoint'             => 'OParl-Endpunkt',
    'validation.form.endpoint.description' => 'Tragen Sie hier den Endpunkt ein, den Sie validieren möchten.',
    'validation.form.endpoint.required'    => 'Sie müssen einen OParl-Endpunkt angeben, der validiert werden soll.',
    'validation.form.endpoint.invalid'     => 'Der angegebene Endpunkt ist keine gültige URL.',
    'validation.form.endpoint.no_oparl'    => 'Unter `:endpoint` konnte kein OParl-System gefunden werden, bitte überprüfen Sie Ihre Eingabe.',
    'validation.form.save'                 => 'Dürfen wir Ihre Validierungsergebnisse zur weiteren Auswertung speichern?',
    'validation.start'                     => 'Validierung beauftragen!',
    'validation.success'                   => 'Die Validierung für :endpoint wurde gestartet.',
    'validation.title'                     => 'Validator',
);

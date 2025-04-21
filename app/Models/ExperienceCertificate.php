<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceCertificate extends Model
{
    protected $table = 'experience_certificates';
    protected $fillable = [
        'id',
        'lang',
        'content',
        'created_by',
    ];



    public static function replaceVariable($content, $obj)
    {
        $arrVariable = [
            '{app_name}',
            '{date}',
            '{employee_name}',
            '{duration}',
            '{designation}',
            '{payroll}',
            

      
        ];
        $arrValue    = [
            'app_name' => '-',
            'date' => '-',
            'employee_name' => '-',
            'duration' => '-',
            'designation' => '-',
            'payroll' => '-',
            

        ];

        foreach($obj as $key => $val)
        {
            $arrValue[$key] = $val;
        }
        $settings = Utility::settings();
       
        $arrValue['app_name']     = env('APP_NAME');
       
       
        return str_replace($arrVariable, array_values($arrValue), $content);
    }
    public static function defaultExpCertificat()
    {
       

        $defaultTemplate = [
              
            'ar' => '<h3 style="text-align: center;">بريد إلكتروني تجربة</h3>
            
            <p>{app_name}</p>
            
            <p>إلي من يهمه الامر</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>مدة الخدمة {duration} في {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>الادوار والمسؤوليات</p>
            
            
            
            <p>وصف موجز لمسار عمل الموظف وبيان إيجابي من المدير أو المشرف.</p>
            
            
            
            <p>بإخلاص،</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>التوقيع</p>
            
            <p>{app_name}</p>',

            
            'da' => '<h3 style="text-align: center;">Erfaringsbrev</h3>
            
            <p>{app_name}</p>
            
            <p>TIL HVEM DET M&Aring;TTE VEDR&Oslash;RE</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Tjenesteperiode {duration} i {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Roller og ansvar</p>
            
            
            
            <p>Kort beskrivelse af medarbejderens ans&aelig;ttelsesforl&oslash;b og positiv udtalelse fra leder eller arbejdsleder.</p>
            
            
            
            <p>Med venlig hilsen</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Underskrift</p>
            
            <p>{app_name}</p>',
            'de' => '<h3 style="text-align: center;">Erfahrungsbrief</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>WEN ES ANGEHT</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Dienstzeit {duration} in {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Rollen und Verantwortlichkeiten</p>
            
            
            
            <p>Kurze Beschreibung des beruflichen Werdegangs des Mitarbeiters und eine positive Stellungnahme des Vorgesetzten oder Vorgesetzten.</p>
            
            
            
            <p>Aufrichtig,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Unterschrift</p>
            
            <p>{app_name}</p>',
            'en' => '<p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: center;" align="center"><span style="font-size: 18pt;"><strong>Experience Letter</strong></span></p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">&nbsp;</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{app_name}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">TO WHOM IT MAY CONCERN</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{date}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{employee_name}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">Tenure of Service {duration} in {app_name}.</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{designation}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{payroll}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">Roles and Responsibilities</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">&nbsp;</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">Brief description of the employee&rsquo;s course of employment and a positive statement from the manager or supervisor.</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">&nbsp;</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">Sincerely,</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{employee_name}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{designation}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">Signature</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{app_name}</p>',
            'es' => '<h3 style="text-align: center;">Carta de experiencia</h3>
            
            
            <p>{app_name}</p>
            
            <p>A QUIEN LE INTERESE</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Duraci&oacute;n del servicio {duration} en {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Funciones y responsabilidades</p>
            
            
            
            <p>Breve descripci&oacute;n del curso de empleo del empleado y una declaraci&oacute;n positiva del gerente o supervisor.</p>
            
            
            
            <p>Sinceramente,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Firma</p>
            
            <p>{app_name}</p>',
            'fr' => '<h3 style="text-align: center;">Lettre dexp&eacute;rience</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>&Agrave; QUI DE DROIT</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Dur&eacute;e du service {duration} dans {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>R&ocirc;les et responsabilit&eacute;s</p>
            
            
            
            <p>Br&egrave;ve description de l&eacute;volution de lemploi de lemploy&eacute; et une d&eacute;claration positive du gestionnaire ou du superviseur.</p>
            
            
            
            <p>Sinc&egrave;rement,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Signature</p>
            
            <p>{app_name}</p>',
            'id' => '<h3 style="text-align: center;">Surat Pengalaman</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>UNTUK PERHATIAN</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Jangka Waktu Layanan {duration} di {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Peran dan Tanggung Jawab</p>
            
            
            
            <p>Deskripsi singkat tentang pekerjaan karyawan dan pernyataan positif dari manajer atau supervisor.</p>
            
            
            
            <p>Sungguh-sungguh,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Tanda tangan</p>
            
            <p>{app_name}</p>',
            'it' => '<h3 style="text-align: center;">Lettera di esperienza</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>PER CHI &Egrave; COINVOLTO</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Durata del servizio {duration} in {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Ruoli e responsabilit&agrave;</p>
            
            
            
            <p>Breve descrizione del percorso lavorativo del dipendente e dichiarazione positiva del manager o supervisore.</p>
            
            
            
            <p>Cordiali saluti,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Firma</p>
            
            <p>{app_name}</p>',
            'ja' => '
            <h3 style="text-align: center;">体験談</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>ご担当者様</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>{app_name} のサービス {duration} の保有期間。</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>役割と責任</p>
            
            
            
            <p>従業員の雇用コースの簡単な説明と、マネージャーまたはスーパーバイザーからの肯定的な声明。</p>
            
            
            
            <p>心から、</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>サイン</p>
            
            <p>{app_name}</p>',
            'nl' => '<h3 style="text-align: center;">Ervaringsbrief</h3>
            
            
            <p>{app_name}</p>
            
            <p>VOOR WIE HET AANGAAT</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Diensttijd {duration} in {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Rollen en verantwoordelijkheden</p>
            
            
            
            <p>Korte omschrijving van het dienstverband van de medewerker en een positieve verklaring van de leidinggevende of leidinggevende.</p>
            
            
            
            <p>Eerlijk,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Handtekening</p>
            
            <p>{app_name}</p>',
            'pl' => '<h3 style="text-align: center;">Doświadczenie List</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>DO TYCH KT&Oacute;RYCH MOŻE TO DOTYCZYĆ</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Okres świadczenia usług {duration} w aplikacji {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Role i obowiązki</p>
            
            
            
            <p>Kr&oacute;tki opis przebiegu zatrudnienia pracownika oraz pozytywna opinia kierownika lub przełożonego.</p>
            
            
            
            <p>Z poważaniem,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Podpis</p>
            
            <p>{app_name}</p>',
            'pt' => '<h3 style="text-align: center;">Carta de Experi&ecirc;ncia</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>A QUEM POSSA INTERESSAR</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Tempo de servi&ccedil;o {duration} em {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Pap&eacute;is e responsabilidades</p>
            
            
            
            <p>Breve descri&ccedil;&atilde;o do curso de emprego do funcion&aacute;rio e uma declara&ccedil;&atilde;o positiva do gerente ou supervisor.</p>
            
            
            
            <p>Sinceramente,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Assinatura</p>
            
            <p>{app_name}</p>',

            'ru' => '<h3 style="text-align: center;">Письмо об опыте</h3>
            
            <p>{app_name}</p>
            
            <p>ДЛЯ ПРЕДЪЯВЛЕНИЯ ПО МЕСТУ ТРЕБОВАНИЯ</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Срок службы {duration} в {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Роли и обязанности</p>
            
            <p>Краткое описание трудового стажа работника и положительное заключение руководителя или руководителя.</p>
            
            <p>Искренне,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Подпись</p>
            
            <p>{app_name}</p>',

            'tr' => '<h3 style="text-align: center;">Tecrübe mektubu</h3>
            
            <p>{app_name}</p>
            
            <p>İLGİLİ MAKAMA</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Hizmet süresi {duration} içinde {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Görev ve Sorumluluklar</p>
            
            <p>Çalışanların istihdam sürecinin kısa açıklaması ve yönetici veya amirden olumlu bir açıklama.</p>
            
            <p>Samimi olarak,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>İmza</p>
            
            <p>{app_name}</p>',

            'zh' => '<h3 style="text-align: center;">经验信</h3>
            
            <p>{app_name}</p>
            
            <p>敬启者</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>服务期限 {duration} 在 {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>角色和责任</p>
            
            <p>员工就业历程的简要描述以及经理或主管的积极声明.</p>
            
            <p>真挚地,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>签名</p>
            
            <p>{app_name}</p>',

            'he' => '<h3 style="text-align: center;">מכתב ניסיון</h3>
            
            <p>{app_name}</p>
            
            <p>לכל מאן דבעי</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>כהונת שירות {duration} ב {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>תפקידים ואחריות</p>
            
            <p>תיאור קצר של מהלך העסקת העובדים והצהרה חיובית מהמנהל או הממונה.</p>
            
            <p>בכנות,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>חֲתִימָה</p>
            
            <p>{app_name}</p>',

            'pt-br' => '<h3 style="text-align: center;">Carta de Experi&ecirc;ncia</h3>
            
            <p>{app_name}</p>
            
            <p>A QUEM POSSA INTERESSAR</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Tempo de servi&ccedil;o {duration} em {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Pap&eacute;is e responsabilidades</p>
            
            <p>Breve descri&ccedil;&atilde;o do curso de emprego do funcion&aacute;rio e uma declara&ccedil;&atilde;o positiva do gerente ou supervisor.</p>
            
            <p>Sinceramente,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Assinatura</p>
            
            <p>{app_name}</p>',
      
     ];

        
        foreach($defaultTemplate as $lang => $content)
        {
            ExperienceCertificate::create(
                [
                    'lang' => $lang,
                    'content' => $content,
                    'created_by' => 2,
                
                ]
            );
        }
        
    }
    public static function defaultExpCertificatRegister($user_id)
    {
       

        $defaultTemplate = [
              
            'ar' => '<h3 style="text-align: center;">بريد إلكتروني تجربة</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>إلي من يهمه الامر</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>مدة الخدمة {duration} في {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>الادوار والمسؤوليات</p>
            
            
            
            <p>وصف موجز لمسار عمل الموظف وبيان إيجابي من المدير أو المشرف.</p>
            
            
            
            <p>بإخلاص،</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>التوقيع</p>
            
            <p>{app_name}</p>',

            
            'da' => '<h3 style="text-align: center;">Erfaringsbrev</h3>
            
            <p>{app_name}</p>
            
            <p>TIL HVEM DET M&Aring;TTE VEDR&Oslash;RE</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Tjenesteperiode {duration} i {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Roller og ansvar</p>
            
            
            
            <p>Kort beskrivelse af medarbejderens ans&aelig;ttelsesforl&oslash;b og positiv udtalelse fra leder eller arbejdsleder.</p>
            
            
            
            <p>Med venlig hilsen</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Underskrift</p>
            
            <p>{app_name}</p>',
            'de' => '<h3 style="text-align: center;">Erfahrungsbrief</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>WEN ES ANGEHT</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Dienstzeit {duration} in {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Rollen und Verantwortlichkeiten</p>
            
            
            
            <p>Kurze Beschreibung des beruflichen Werdegangs des Mitarbeiters und eine positive Stellungnahme des Vorgesetzten oder Vorgesetzten.</p>
            
            
            
            <p>Aufrichtig,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Unterschrift</p>
            
            <p>{app_name}</p>',
            'en' => '<p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: center;" align="center"><span style="font-size: 18pt;"><strong>Experience Letter</strong></span></p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">&nbsp;</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{app_name}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">TO WHOM IT MAY CONCERN</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{date}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{employee_name}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">Tenure of Service {duration} in {app_name}.</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{designation}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{payroll}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">Roles and Responsibilities</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">&nbsp;</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">Brief description of the employee&rsquo;s course of employment and a positive statement from the manager or supervisor.</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">&nbsp;</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">Sincerely,</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{employee_name}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{designation}</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">Signature</p>
            <p lang="en-IN" style="margin-bottom: 0cm; direction: ltr; line-height: 2; text-align: left;" align="center">{app_name}</p>',
            'es' => '<h3 style="text-align: center;">Carta de experiencia</h3>
            
            
            <p>{app_name}</p>
            
            <p>A QUIEN LE INTERESE</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Duraci&oacute;n del servicio {duration} en {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Funciones y responsabilidades</p>
            
            
            
            <p>Breve descripci&oacute;n del curso de empleo del empleado y una declaraci&oacute;n positiva del gerente o supervisor.</p>
            
            
            
            <p>Sinceramente,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Firma</p>
            
            <p>{app_name}</p>',
            'fr' => '<h3 style="text-align: center;">Lettre dexp&eacute;rience</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>&Agrave; QUI DE DROIT</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Dur&eacute;e du service {duration} dans {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>R&ocirc;les et responsabilit&eacute;s</p>
            
            
            
            <p>Br&egrave;ve description de l&eacute;volution de lemploi de lemploy&eacute; et une d&eacute;claration positive du gestionnaire ou du superviseur.</p>
            
            
            
            <p>Sinc&egrave;rement,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Signature</p>
            
            <p>{app_name}</p>',
            'id' => '<h3 style="text-align: center;">Surat Pengalaman</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>UNTUK PERHATIAN</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Jangka Waktu Layanan {duration} di {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Peran dan Tanggung Jawab</p>
            
            
            
            <p>Deskripsi singkat tentang pekerjaan karyawan dan pernyataan positif dari manajer atau supervisor.</p>
            
            
            
            <p>Sungguh-sungguh,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Tanda tangan</p>
            
            <p>{app_name}</p>',
            'it' => '<h3 style="text-align: center;">Lettera di esperienza</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>PER CHI &Egrave; COINVOLTO</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Durata del servizio {duration} in {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Ruoli e responsabilit&agrave;</p>
            
            
            
            <p>Breve descrizione del percorso lavorativo del dipendente e dichiarazione positiva del manager o supervisore.</p>
            
            
            
            <p>Cordiali saluti,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Firma</p>
            
            <p>{app_name}</p>',
            'ja' => '
            <h3 style="text-align: center;">体験談</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>ご担当者様</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>{app_name} のサービス {duration} の保有期間。</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>役割と責任</p>
            
            
            
            <p>従業員の雇用コースの簡単な説明と、マネージャーまたはスーパーバイザーからの肯定的な声明。</p>
            
            
            
            <p>心から、</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>サイン</p>
            
            <p>{app_name}</p>',
            'nl' => '<h3 style="text-align: center;">Ervaringsbrief</h3>
            
            
            <p>{app_name}</p>
            
            <p>VOOR WIE HET AANGAAT</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Diensttijd {duration} in {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Rollen en verantwoordelijkheden</p>
            
            
            
            <p>Korte omschrijving van het dienstverband van de medewerker en een positieve verklaring van de leidinggevende of leidinggevende.</p>
            
            
            
            <p>Eerlijk,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Handtekening</p>
            
            <p>{app_name}</p>',
            'pl' => '<h3 style="text-align: center;">Doświadczenie List</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>DO TYCH KT&Oacute;RYCH MOŻE TO DOTYCZYĆ</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Okres świadczenia usług {duration} w aplikacji {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Role i obowiązki</p>
            
            
            
            <p>Kr&oacute;tki opis przebiegu zatrudnienia pracownika oraz pozytywna opinia kierownika lub przełożonego.</p>
            
            
            
            <p>Z poważaniem,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Podpis</p>
            
            <p>{app_name}</p>',
            'pt' => '<h3 style="text-align: center;">Carta de Experi&ecirc;ncia</h3>
            
            <p>{app_name}</p>
            
            <p>A QUEM POSSA INTERESSAR</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Tempo de servi&ccedil;o {duration} em {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Pap&eacute;is e responsabilidades</p>
            
            <p>Breve descri&ccedil;&atilde;o do curso de emprego do funcion&aacute;rio e uma declara&ccedil;&atilde;o positiva do gerente ou supervisor.</p>
            
            <p>Sinceramente,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Assinatura</p>
            
            <p>{app_name}</p>',
            
            'ru' => '<h3 style="text-align: center;">Письмо об опыте</h3>
            
            
            
            <p>{app_name}</p>
            
            <p>ДЛЯ ПРЕДЪЯВЛЕНИЯ ПО МЕСТУ ТРЕБОВАНИЯ</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Срок службы {duration} в {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Роли и обязанности</p>
            
            
            
            <p>Краткое описание трудового стажа работника и положительное заключение руководителя или руководителя.</p>
            
            
            
            <p>Искренне,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Подпись</p>
            
            <p>{app_name}</p>',

            'tr' => '<h3 style="text-align: center;">Tecrübe mektubu</h3>
            
            <p>{app_name}</p>
            
            <p>İLGİLİ MAKAMA</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Hizmet süresi {duration} içinde {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Görev ve Sorumluluklar</p>
            
            <p>Çalışanların istihdam sürecinin kısa açıklaması ve yönetici veya amirden olumlu bir açıklama.</p>
            
            <p>Samimi olarak,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>İmza</p>
            
            <p>{app_name}</p>',

            'zh' => '<h3 style="text-align: center;">经验信</h3>
            
            <p>{app_name}</p>
            
            <p>敬启者</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>服务期限 {duration} 在 {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>角色和责任</p>
            
            <p>员工就业历程的简要描述以及经理或主管的积极声明.</p>
            
            <p>真挚地,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>签名</p>
            
            <p>{app_name}</p>',

            'he' => '<h3 style="text-align: center;">מכתב ניסיון</h3>
            
            <p>{app_name}</p>
            
            <p>לכל מאן דבעי</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>כהונת שירות {duration} ב {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>תפקידים ואחריות</p>
            
            <p>תיאור קצר של מהלך העסקת העובדים והצהרה חיובית מהמנהל או הממונה.</p>
            
            <p>בכנות,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>חֲתִימָה</p>
            
            <p>{app_name}</p>',

            'pt-br' => '<h3 style="text-align: center;">Carta de Experi&ecirc;ncia</h3>
            
            <p>{app_name}</p>
            
            <p>A QUEM POSSA INTERESSAR</p>
            
            <p>{date}</p>
            
            <p>{employee_name}</p>
            
            <p>Tempo de servi&ccedil;o {duration} em {app_name}.</p>
            
            <p>{designation}</p>
            
            <p>{payroll}</p>
            
            <p>Pap&eacute;is e responsabilidades</p>
            
            <p>Breve descri&ccedil;&atilde;o do curso de emprego do funcion&aacute;rio e uma declara&ccedil;&atilde;o positiva do gerente ou supervisor.</p>
            
            <p>Sinceramente,</p>
            
            <p>{employee_name}</p>
            
            <p>{designation}</p>
            
            <p>Assinatura</p>
            
            <p>{app_name}</p>',
      
     ];

        
        foreach($defaultTemplate as $lang => $content)
        {
            ExperienceCertificate::create(
                [
                    'lang' => $lang,
                    'content' => $content,
                    'created_by' => $user_id,
                
                ]
            );
        }
        
    }
}

<?php

namespace Database\Seeders;

use App\Models\NotificationTemplateLangs;
use App\Models\NotificationTemplates;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notifications = [
            'new_monthly_payslip' => 'New Monthly Payslip',
            'new_announcement' => 'New Announcement',
            'new_meeting' => 'New Meeting',
            'new_award' => 'New Award',
            'new_holidays' => 'New Holidays',
            'new_company_policy' => 'New Company Policy',
            'new_ticket' => 'New Ticket',
            'new_event' => 'New Event',
            'leave_approve_reject' => 'Leave Approve/Reject',
            'new_trip' => 'New Trip',
            'contract_notification' => 'New Contract',
        ];

        $defaultTemplate = [
            'new_monthly_payslip' => [
                'variables' => '{
                    "Year": "year"
                  }',
                  'lang' => [
                      'ar' => 'تم إنشاء قسيمة دفع بتاريخ {year}.',
                      'da' => 'Lønseddel genereret af {year}.',
                      'de' => 'Gehaltsabrechnung erstellt vom {year}.',
                      'en' => 'Payslip generated of {year}.',
                      'es' => 'Nómina generada de {year}.',
                      'fr' => 'Fiche de paie générée de {year}.',
                      'it' => 'Busta paga generata da {year}.',
                      'ja' => '{year} の給与明細が作成されました。',
                      'nl' => 'Loonstrook gegenereerd van {year}.',
                      'pl' => 'Odcinek wypłaty wygenerowany za {year}.',
                      'pt' => 'Folha de pagamento gerada de {year}.',
                      'ru' => 'Расчетная ведомость создана за {year}.',
                      'tr' => 'oluşturulan maaş bordrosu {year}.',
                      'zh' => '生成的工资单 {year}',
                      'he' => 'תלוש שכר שנוצר מ {year}.',
                      'pt-br' => 'Folha de pagamento gerada de {year}.',
                  ]
            ],
            'new_announcement' => [
                'variables' => '{
                    "Announcement Title": "announcement_title",
                    "Branch name": "branch_name",
                    "Start Date": "start_date",
                    "End Date": "end_date"
                  }',
                'lang' => [
                    'ar' => '{announcement_title} إعلان تم إنشاؤه للفرع {branch_name} من {start_date} ل {end_date}',
                    'da' => '{announcement_title} meddelelse oprettet for filial {branch_name} fra {start_date} to {end_date}',
                    'de' => '{announcement_title} Ankündigung für Filiale erstellt {branch_name} aus {start_date} Zu {end_date}',
                    'en' => '{announcement_title} announcement created for branch {branch_name} from {start_date} to {end_date}',
                    'es' => '{announcement_title} anuncio creado para sucursal {branch_name} de {start_date} a {end_date}',
                    'fr' => "{announcement_title} annonce créée pour la filiale {branch_name} depuis {start_date} pour {end_date}",
                    'it' => '{announcement_title} annuncio creato per branch {branch_name} da {start_date} A {end_date}',
                    'ja' => '{announcement_title} ブランチ用に作成されたお知らせ {branch_name} から {start_date} に {end_date}',
                    'nl' => '{announcement_title} aankondiging gemaakt voor filiaal {branch_name} van {start_date} naar {end_date}',
                    'pl' => '{announcement_title} ogłoszenie stworzone dla oddziału {branch_name} z {start_date} Do {end_date}',
                    'pt' => '{announcement_title} anúncio criado para filial {branch_name} de {start_date} para {end_date}',
                    'ru' => '{announcement_title} объявление создано для ветки {branch_name} от {start_date} к {end_date}',
                    'tr' => '{announcement_title} şube için oluşturulan duyuru {branch_name} itibaren {start_date} ile {end_date}',
                    'zh' => '{announcement_title} 为分支机构创建的公告 {branch_name} 从 {start_date} 到 {end_date}',
                    'he' => '{announcement_title} הודעה נוצרה עבור הסניף {branch_name} מ {start_date} ל {end_date}',
                    'pt-br' => '{announcement_title} anúncio criado para filial {branch_name} de {start_date} para {end_date}',
                ],
            ],
            'new_meeting' => [
                'variables' => '{
                    "Meeting title": "meeting_title",
                    "Branch name": "branch_name",
                    "Date": "date",
                    "Time": "time"
                  }',
                'lang' => [
                    'ar' => '{meeting_title} تم إنشاء الاجتماع لـ {branch_name} من {date} في {time}.',
                    'da' => '{meeting_title} møde oprettet til {branch_name} fra {date} på {time}.',
                    'de' => '{meeting_title} Besprechung erstellt für {branch_name} aus {date} bei {time}.',
                    'en' => '{meeting_title} meeting created for {branch_name} from {date} at {time}.',
                    'es' => '{meeting_title} reunión creada para {branch_name} de {date} en {time}.',
                    'fr' => "{meeting_title} réunion créée pour {branch_name} depuis {date} à {time}.",
                    'it' => '{meeting_title} incontro creato per {branch_name} da {date} A {time}.',
                    'ja' => '{meeting_title} のために作成された会議 {branch_name} から {date} で {time}.',
                    'nl' => '{meeting_title} bijeenkomst gemaakt voor {branch_name} van {date} bij {time}.',
                    'pl' => '{meeting_title} spotkanie stworzone dla {branch_name} z {date} Na {time}.',
                    'pt' => '{meeting_title} reunião criada para {branch_name} de {date} no {time}.',
                    'ru' => '{meeting_title} встреча создана для {branch_name} от {date} в {time}.',
                    'tr' => '{meeting_title} için oluşturulan toplantı {branch_name} itibaren {date} de {time}.',
                    'zh' => '{meeting_title} 为以下目的创建的会议 {branch_name} 从 {date} 在 {time}.',
                    'he' => '{meeting_title} פגישה נוצרה עבור {branch_name} מ {date} בְּ- {time}.',
                    'pt-br' => '{meeting_title} reunião criada para {branch_name} de {date} no {time}.',
                ],
            ],
            'new_award' => [
                'variables' => '{
                    "Award name": "award_name",
                    "Employee Name": "employee_name",
                    "Date": "date"
                  }',
                'lang' => [
                    'ar' => '{award_name} خلقت ل {employee_name} من {date}.',
                    'da' => '{award_name} skabt til {employee_name} fra {date}.',
                    'de' => '{award_name} hergestellt für {employee_name} aus {date}.',
                    'en' => '{award_name} created for {employee_name} from {date}.',
                    'es' => '{award_name} creado para {employee_name} de {date}.',
                    'fr' => '{award_name} créé pour {employee_name} depuis {date}.',
                    'it' => '{award_name} creato per {employee_name} da {date}.',
                    'ja' => '{award_name} のために作成された {employee_name} から {date}.',
                    'nl' => '{award_name} gemaakt voor {employee_name} van {date}.',
                    'pl' => '{award_name} stworzone dla {employee_name} z {date}.',
                    'pt' => '{award_name} criado para {employee_name} de {date}.',
                    'ru' => '{award_name} предназначен для {employee_name} от {date}.',
                    'tr' => '{award_name} için yaratıldı {employee_name} itibaren {date}.',
                    'zh' => '{award_name} 已创建 为了 {employee_name} 从 {date}.',
                    'he' => '{award_name} נוצר עבור {employee_name} מ {date}.',
                    'pt-br' => '{award_name} criado para {employee_name} de {date}.',
                ],
            ],
            'new_holidays' => [
                'variables' => '{
                    "Occasion name": "occasion_name",
                    "Start Date": "start_date",
                    "End Date": "end_date"
                  }',
                'lang' => [
                    'ar' => '{occasion_name} على {start_date} ل {end_date}.',
                    'da' => '{occasion_name} på {start_date} til {end_date}.',
                    'de' => '{occasion_name} An {start_date} Zu {end_date}.',
                    'en' => '{occasion_name} on {start_date} to {end_date}.',
                    'es' => '{occasion_name} en {start_date} a {end_date}.',
                    'fr' => '{occasion_name} sur {start_date} pour {end_date}.',
                    'it' => '{occasion_name} SU {start_date} A {end_date}.',
                    'ja' => '{occasion_name} の上 {start_date} に {end_date}.',
                    'nl' => '{occasion_name} op {start_date} naar {end_date}.',
                    'pl' => '{occasion_name} NA {start_date} Do {end_date}.',
                    'pt' => '{occasion_name} sobre {start_date} para {end_date}.',
                    'ru' => '{occasion_name} на {start_date} к {end_date}.',
                    'tr' => '{occasion_name} Açık {start_date} ile {end_date}.',
                    'zh' => '{occasion_name} 在 {start_date} 到 {end_date}.',
                    'he' => '{occasion_name} עַל {start_date} ל {end_date}.',
                    'pt-br' => '{occasion_name} sobre {start_date} para {end_date}.',
                ],
            ],
            'new_company_policy' => [
                'variables' => '{
                    "Company policy name": "company_policy_name",
                    "Branch name": "branch_name"
                  }',
                'lang' => [
                    'ar' => '{company_policy_name} ل {branch_name} مخلوق.',
                    'da' => '{company_policy_name} til {branch_name} oprettet.',
                    'de' => '{company_policy_name} für {branch_name} erstellt.',
                    'en' => '{company_policy_name} for {branch_name} created.',
                    'es' => '{company_policy_name} para {branch_name} creada.',
                    'fr' => '{company_policy_name} pour {branch_name} créé.',
                    'it' => '{company_policy_name} per {branch_name} creata.',
                    'ja' => '{company_policy_name} ために {branch_name} 作成した.',
                    'nl' => '{company_policy_name} voor {branch_name} gemaakt.',
                    'pl' => '{company_policy_name} Do {branch_name} Utworzony.',
                    'pt' => '{company_policy_name} para {branch_name} criada.',
                    'ru' => '{company_policy_name} для {branch_name} созданный.',
                    'tr' => '{company_policy_name} için {branch_name} oluşturuldu.',
                    'zh' => '{company_policy_name} 为了 {branch_name} 已创建.',
                    'he' => '{company_policy_name} ל {branch_name} נוצר.',
                    'pt-br' => '{company_policy_name} para {branch_name} criada.',
                ],
            ],
            'new_ticket' => [
                'variables' => '{
                    "Ticket priority": "ticket_priority",
                    "Employee Name": "employee_name"
                  }',
                'lang' => [
                    'ar' => 'تم إنشاء تذكرة دعم جديدة من {ticket_priority} الأولوية ل {employee_name}.',
                    'da' => 'Ny supportbillet oprettet af {ticket_priority} prioritet for {employee_name}.',
                    'de' => 'Neues Support-Ticket erstellt von {ticket_priority} Priorität für {employee_name}.',
                    'en' => 'New Support ticket created of {ticket_priority} priority for {employee_name}.',
                    'es' => 'Nuevo ticket de soporte creado de {ticket_priority} prioridad para {employee_name}.',
                    'fr' => 'Nouveau ticket de support créé de {ticket_priority} priorité pour {employee_name}.',
                    'it' => 'Nuovo ticket di supporto creato da {ticket_priority} priorità per {employee_name}.',
                    'ja' => 'の新しいサポート チケットが作成されました {ticket_priority} の優先順位 {employee_name}.',
                    'nl' => 'Nieuw supportticket gemaakt van {ticket_priority} prioriteit voor {employee_name}.',
                    'pl' => 'Utworzono nowe zgłoszenie do pomocy technicznej {ticket_priority} priorytet dla {employee_name}.',
                    'pt' => 'Novo ticket de suporte criado de {ticket_priority} prioridade para {employee_name}.',
                    'ru' => 'Создан новый тикет в службу поддержки {ticket_priority} приоритет для {employee_name}.',
                    'tr' => 'Şunun için yeni Destek bileti oluşturuldu {ticket_priority} için öncelik {employee_name}.',
                    'zh' => '新的支持票证创建于 {ticket_priority} 优先于 {employee_name}.',
                    'he' => 'כרטיס תמיכה חדש נוצר מ {ticket_priority} עדיפות עבור {employee_name}.',
                    'pt-br' => 'Novo ticket de suporte criado de {ticket_priority} prioridade para {employee_name}.',
                ],
            ],
            'new_event' => [
                'variables' => '{
                    "Event name": "event_name",
                    "Branch name": "branch_name",
                    "Start Date": "start_date",
                    "End Date": "end_date"
                  }',
                'lang' => [
                    'ar' => '{event_name} للفرع {branch_name} من {start_date} ل {end_date}',
                    'da' => '{event_name} for filial {branch_name} fra {start_date} til {end_date}',
                    'de' => '{event_name} für Filiale {branch_name} aus {start_date} Zu {end_date}',
                    'en' => '{event_name} for branch {branch_name} from {start_date} to {end_date}',
                    'es' => '{event_name} para rama {branch_name} de {start_date} a {end_date}',
                    'fr' => '{event_name} pour la branche {branch_name} depuis {start_date} pour {end_date}',
                    'it' => '{event_name} per ramo {branch_name} da {start_date} A {end_date}',
                    'ja' => '{event_name} 支店用 {branch_name} から {start_date} に {end_date}',
                    'nl' => '{event_name} voor filiaal {branch_name} van {start_date} naar {end_date}',
                    'pl' => '{event_name} dla oddziału {branch_name} z {start_date} Do {end_date}',
                    'pt' => '{event_name} para ramo {branch_name} de {start_date} para {end_date}',
                    'ru' => '{event_name} для филиала {branch_name} от {start_date} к {end_date}',
                    'tr' => '{event_name} şube için {branch_name} itibaren {start_date} ile {end_date}',
                    'zh' => '{event_name} 对于分支机构 {branch_name} 从 {start_date} 到 {end_date}',
                    'he' => '{event_name} עבור סניף {branch_name} מ {start_date} ל {end_date}',
                    'pt-br' => '{event_name} para ramo {branch_name} de {start_date} para {end_date}',
                ],
            ],
            'leave_approve_reject' => [
                'variables' => '{
                    "Leave Status": "leave_status"
                  }',
                'lang' => [
                    'ar' => 'لقد كانت إجازتك {leave_status}.',
                    'da' => 'Din orlov har været {leave_status}.',
                    'de' => 'Ihr Urlaub war {leave_status}.',
                    'en' => 'Your leave has been {leave_status}.',
                    'es' => 'Tu permiso ha sido {leave_status}.',
                    'fr' => 'Votre congé a été {leave_status}.',
                    'it' => 'Il tuo congedo è stato {leave_status}.',
                    'ja' => 'あなたの休暇は {leave_status}.',
                    'nl' => 'Je verlof is geweest {leave_status}.',
                    'pl' => 'Twój urlop był {leave_status}.',
                    'pt' => 'sua licença foi {leave_status}.',
                    'ru' => 'Ваш отпуск был {leave_status}.',
                    'tr' => 'İzniniz oldu {leave_status}.',
                    'zh' => '你的假期已经 {leave_status}.',
                    'he' => 'החופש שלך היה {leave_status}.',
                    'pt-br' => 'sua licença foi {leave_status}.',
                ],
            ],
            'new_trip' => [
                'variables' => '{
                    "Purpose of visit": "purpose_of_visit",
                    "Place of visit": "place_of_visit",
                    "Employee Name": "employee_name",
                    "Start Date": "start_date",
                    "End Date": "end_date"
                  }',
                'lang' => [
                    'ar' => '{purpose_of_visit} تم إنشاؤه للزيارة {place_of_visit} ل {employee_name} من {start_date} ل {end_date}.',
                    'da' => '{purpose_of_visit} er skabt til at besøge {place_of_visit} til {employee_name} fra {start_date} til {end_date}.',
                    'de' => '{purpose_of_visit} ist zum Besuchen angelegt {place_of_visit} für {employee_name} aus {start_date} Zu {end_date}.',
                    'en' => '{purpose_of_visit} is created to visit {place_of_visit} for {employee_name} from {start_date} to {end_date}.',
                    'es' => '{purpose_of_visit} se crea para visitar {place_of_visit} para {employee_name} de {start_date} a {end_date}.',
                    'fr' => '{purpose_of_visit} est créé pour visiter {place_of_visit} pour {employee_name} depuis {start_date} pour {end_date}.',
                    'it' => '{purpose_of_visit} è creato per visitare {place_of_visit} for {employee_name} per {start_date} A {end_date}.',
                    'ja' => '{purpose_of_visit} 訪問するために作成されます {place_of_visit} ために {employee_name} から {start_date} に {end_date}.',
                    'nl' => '{purpose_of_visit} is gemaakt om te bezoeken {place_of_visit} voor {employee_name} van {start_date} naar {end_date}.',
                    'pl' => '{purpose_of_visit} jest stworzony do zwiedzania {place_of_visit} Do {employee_name} z {start_date} Do {end_date}.',
                    'pt' => '{purpose_of_visit} é criado para visitar {place_of_visit} para {employee_name} de {start_date} para {end_date}.',
                    'ru' => '{purpose_of_visit} создан для посещения {place_of_visit} для {employee_name} от {start_date} к {end_date}.',
                    'tr' => '{purpose_of_visit} ziyaret etmek için yaratılmıştır {place_of_visit} için {employee_name} itibaren {start_date} ile {end_date}.',
                    'zh' => '{purpose_of_visit} 被创建来访问 {place_of_visit} 为了 {employee_name} 从 {start_date} 到 {end_date}.',
                    'he' => '{purpose_of_visit} נוצר כדי לבקר {place_of_visit} ל {employee_name} מ {start_date} ל {end_date}.',
                    'pt-br' => '{purpose_of_visit} é criado para visitar {place_of_visit} para {employee_name} de {start_date} para {end_date}.',
                ],
            ],
            'contract_notification' => [
                'variables' => '{
                    "Contract number": "contract_number",
                    "Contract company name": "contract_company_name"
                  }',
                'lang' => [
                    'ar' => 'تم إنشاء الفاتورة الجديدة {contract_number} بواسطة {contract_company_name}.',
                    'da' => 'Ny faktura {contract_number} oprettet af {contract_company_name}.',
                    'de' => 'Neue Rechnung {contract_number} erstellt von {contract_company_name}.',
                    'en' => 'New Invoice {contract_number} created by {contract_company_name}.',
                    'es' => 'Nueva factura {contract_number} creada por {contract_company_name}.',
                    'fr' => 'Nouvelle facture {contract_number} créée par {contract_company_name}.',
                    'it' => 'Nuova fattura {contract_number} creata da {contract_company_name}.',
                    'ja' => '{contract_company_name} によって作成された新しい請求書 {contract_number}。',
                    'nl' => 'Nieuwe factuur {contract_number} gemaakt door {contract_company_name}.',
                    'pl' => 'Nowa faktura {contract_number} utworzona przez firmę {contract_company_name}.',
                    'pt' => 'Nova fatura {contract_number} criada por {contract_company_name}.',
                    'ru' => 'Новый счет {contract_number}, созданный {contract_company_name}.',
                    'tr' => 'Yeni fatura {contract_number} tarafından yaratıldı {contract_company_name}.',
                    'zh' => '新发票 {contract_number} 由...制作 {contract_company_name}.',
                    'he' => 'חשבונית חדשה {contract_number} נוצר על ידי {contract_company_name}.',
                    'pt-br' => 'Nova fatura {contract_number} criada por {contract_company_name}.',
                ],
            ],
        ];

        // $user = User::where('type', 'super admin')->first();

        foreach ($notifications as $k => $n) {
            $ntfy = NotificationTemplates::where('slug', $k)->count();
            if ($ntfy == 0) {
                $new = new NotificationTemplates();
                $new->name = $n;
                $new->slug = $k;
                $new->save();

                foreach ($defaultTemplate[$k]['lang'] as $lang => $content) {
                    NotificationTemplateLangs::create(
                        [
                            'parent_id' => $new->id,
                            'lang' => $lang,
                            'variables' => $defaultTemplate[$k]['variables'],
                            'content' => $content,
                            // 'created_by' => !empty($user) ? $user->id : 1,
                            'created_by' => 1,
                        ]
                    );
                }
            }
        }
    }
}

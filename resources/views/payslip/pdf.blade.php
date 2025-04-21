@php
    // $logo = asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_logo = App\Models\Utility::get_company_logo();

@endphp
<div class="modal-body">
    <div class="text-md-end mb-2">
        <a href="#" class="btn btn-sm btn-primary me-1" data-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Download') }}" onclick="saveAsPDF()"><span class="fa fa-download"></span></a>

        @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr')
            <a href="{{ route('payslip.send', [$employee->id, $payslip->salary_month]) }}"
                class="btn btn-sm btn-warning" data-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Mail Send') }}"><span class="fa fa-paper-plane"></span></a>
        @endif
    </div>
    <div class="invoice" id="printableArea">
        <div class="row">
            <div class="col-form-label">
                <div class="invoice-number">
                    <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}"
                        width="170px;">
                </div>

                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                {{-- <h6 class="mb-3">{{ __('Payslip') }}</h6> --}}

                            </div>
                            <hr>
                            <div class="row text-sm">
                                <div class="col-md-6">
                                    <address>
                                        <strong>{{ __('Name') }} :</strong> {{ $employee->name }}<br>
                                        <strong>{{ __('Position') }} :</strong> {{ $employee->designation->name }}<br>
                                        <strong>{{ __('Salary Date') }} :</strong>
                                        {{ \Auth::user()->dateFormat($payslip->created_at) }}<br>
                                    </address>
                                </div>
                                <div class="col-md-6 text-end">
                                    <address>
                                        <strong>{{ App\Models\Utility::getValByName('company_name') }} </strong><br>
                                        {{ App\Models\Utility::getValByName('company_address') }} ,
                                        {{ App\Models\Utility::getValByName('company_city') }},<br>
                                        {{ App\Models\Utility::getValByName('company_state') }}-{{ App\Models\Utility::getValByName('company_zipcode') }}<br>
                                        <strong>{{ __('Salary Slip') }} :</strong> {{ $payslip->salary_month }}<br>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table  table-md">
                                    <tbody>
                                        <tr class="font-weight-bold">
                                            <th>{{ __('Earning') }}</th>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th class="text-right">{{ __('Amount') }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Basic Salary') }}</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td class="text-right">
                                                {{ \Auth::user()->priceFormat($payslip->basic_salary) }}</td>
                                        </tr>

                                        @foreach ($payslipDetail['earning']['allowance'] as $allowance)
                                            @php
                                                $employess = \App\Models\Employee::find($allowance->employee_id);
                                                $allowance = json_decode($allowance->allowance);
                                            @endphp
                                            @foreach ($allowance as $all)
                                                <tr>
                                                    <td>{{ __('Allowance') }}</td>
                                                    <td>{{ $all->title }}</td>
                                                    <td>{{ ucfirst($all->type) }}</td>
                                                    @if ($all->type != 'percentage')
                                                        <td class="text-right">
                                                            {{ \Auth::user()->priceFormat($all->amount) }}</td>
                                                    @else
                                                        <td class="text-right">{{ $all->amount }}%
                                                            ({{ \Auth::user()->priceFormat(($all->amount * $payslip->basic_salary) / 100) }})
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach

                                        @foreach ($payslipDetail['earning']['commission'] as $commission)
                                            @php
                                                $employess = \App\Models\Employee::find($commission->employee_id);
                                                $commissions = json_decode($commission->commission);
                                            @endphp
                                            @foreach ($commissions as $empcom)
                                                <tr>
                                                    <td>{{ __('Commission') }}</td>
                                                    <td>{{ $empcom->title }}</td>
                                                    <td>{{ ucfirst($empcom->type) }}</td>
                                                    @if ($empcom->type != 'percentage')
                                                        <td class="text-right">
                                                            {{ \Auth::user()->priceFormat($empcom->amount) }}</td>
                                                    @else
                                                        <td class="text-right">{{ $empcom->amount }}%
                                                            ({{ \Auth::user()->priceFormat(($empcom->amount * $payslip->basic_salary) / 100) }})
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach

                                        @foreach ($payslipDetail['earning']['otherPayment'] as $otherPayment)
                                            @php
                                                $employess = \App\Models\Employee::find($otherPayment->employee_id);
                                                $otherpay = json_decode($otherPayment->other_payment);
                                            @endphp
                                            @foreach ($otherpay as $op)
                                                <tr>
                                                    <td>{{ __('Other Payment') }}</td>
                                                    <td>{{ $op->title }}</td>
                                                    <td>{{ ucfirst($op->type) }}</td>
                                                    @if ($op->type != 'percentage')
                                                        <td class="text-right">
                                                            {{ \Auth::user()->priceFormat($op->amount) }}</td>
                                                    @else
                                                        <td class="text-right">{{ $op->amount }}%
                                                            ({{ \Auth::user()->priceFormat(($op->amount * $payslip->basic_salary) / 100) }})
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach

                                        @foreach ($payslipDetail['earning']['overTime'] as $overTime)
                                            @php
                                                $arrayJson = json_decode($overTime->overtime);
                                                foreach ($arrayJson as $key => $overtime) {
                                                    foreach ($arrayJson as $key => $overtimes) {
                                                        $overtitle = $overtimes->title;
                                                        $OverTime = $overtimes->number_of_days * $overtimes->hours * $overtimes->rate;
                                                    }
                                                }
                                            @endphp
                                            @foreach ($arrayJson as $overtime)
                                                <tr>
                                                    <td>{{ __('OverTime') }}</td>
                                                    <td>{{ $overtime->title }}</td>
                                                    <td>-</td>
                                                    <td class="text-right">
                                                        {{ \Auth::user()->priceFormat($overtime->number_of_days * $overtime->hours * $overtime->rate) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tbody>
                                        <tr class="font-weight-bold">
                                            <th>{{ __('Deduction') }}</th>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('type') }}</th>
                                            <th class="text-right">{{ __('Amount') }}</th>
                                        </tr>

                                        @foreach ($payslipDetail['deduction']['loan'] as $loan)
                                            @php
                                                $employess = \App\Models\Employee::find($loan->employee_id);
                                                $loans = json_decode($loan->loan);
                                            @endphp
                                            @foreach ($loans as $emploanss)
                                                <tr>
                                                    <td>{{ __('Loan') }}</td>
                                                    <td>{{ $emploanss->title }}</td>
                                                    <td>{{ ucfirst($emploanss->type) }}</td>
                                                    @if ($emploanss->type != 'percentage')
                                                        <td class="text-right">
                                                            {{ \Auth::user()->priceFormat($emploanss->amount) }}</td>
                                                    @else
                                                        <td class="text-right">{{ $emploanss->amount }}%
                                                            ({{ \Auth::user()->priceFormat(($emploanss->amount * $payslip->basic_salary) / 100) }})
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach

                                        @foreach ($payslipDetail['deduction']['deduction'] as $deduction)
                                            @php
                                                $employess = \App\Models\Employee::find($deduction->employee_id);
                                                $deductions = json_decode($deduction->saturation_deduction);
                                            @endphp
                                            @foreach ($deductions as $saturationdeduc)
                                                <tr>
                                                    <td>{{ __('Saturation Deduction') }}</td>
                                                    <td>{{ $saturationdeduc->title }}</td>
                                                    <td>{{ ucfirst($saturationdeduc->type) }}</td>
                                                    @if ($saturationdeduc->type != 'percentage')
                                                        <td class="text-right">
                                                            {{ \Auth::user()->priceFormat($saturationdeduc->amount) }}
                                                        </td>
                                                    @else
                                                        <td class="text-right">{{ $saturationdeduc->amount }}%
                                                            ({{ \Auth::user()->priceFormat(($saturationdeduc->amount * $payslip->basic_salary) / 100) }})
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-8">

                                </div>
                                <div class="col-lg-4 text-right text-sm">
                                    <div class="invoice-detail-item pb-2">
                                        <div class="invoice-detail-name font-weight-bold">{{ __('Total Earning') }}
                                        </div>
                                        <div class="invoice-detail-value">
                                            {{ \Auth::user()->priceFormat($payslipDetail['totalEarning']) }}</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name font-weight-bold">{{ __('Total Deduction') }}
                                        </div>
                                        <div class="invoice-detail-value">
                                            {{ \Auth::user()->priceFormat($payslipDetail['totalDeduction']) }}</div>
                                    </div>
                                    <hr class="mt-2 mb-2">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name font-weight-bold">{{ __('Net Salary') }}</div>
                                        <div class="invoice-detail-value invoice-detail-value-lg">
                                            {{ \Auth::user()->priceFormat($payslip->net_payble) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-md-right pb-2 text-sm">
                    <div class="float-lg-left mb-lg-0 mb-3 ">
                        <p class="mt-2">{{ __('Employee Signature') }}</p>
                    </div>
                    <p class="mt-2 "> {{ __('Paid By') }}</p>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
<script>
    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var opt = {
            margin: 0.3,
            filename: '{{ $employee->name }}',
            image: {
                type: 'jpeg',
                quality: 1
            },
            html2canvas: {
                scale: 4,
                dpi: 72,
                letterRendering: true
            },
            jsPDF: {
                unit: 'in',
                format: 'A4'
            }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>

<ul class="nav nav-pills nav-fill information-tab hrm_setup_tab" id="pills-tab" role="tablist">
    @can('Manage Branch')
        <li class="nav-item" role="presentation">
            <a href="{{ route('branch.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ Request::route()->getName() == 'branch.index' ? 'active' : '' }}"
                    id="branch-setting-tab" data-bs-toggle="pill" data-bs-target="#branch-setting"
                    type="button">{{ __('Branch') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Department')
        <li class="nav-item" role="presentation">
            <a href="{{ route('department.index') }}" class="list-group-item list-group-item-action border-0 ">
                <button class="nav-link {{ Request::route()->getName() == 'department.index' ? 'active' : '' }}"
                    id="department-setting-tab" data-bs-toggle="pill" data-bs-target="#department-setting"
                    type="button">{{ __('Department') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Designation')
        <li class="nav-item" role="presentation">
            <a href="{{ route('designation.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('designation*') ? 'active' : '' }}" id="designation-setting-tab"
                    data-bs-toggle="pill" data-bs-target="#designation-setting"
                    type="button">{{ __('Designation') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Leave Type')
        <li class="nav-item" role="presentation">
            <a href="{{ route('leavetype.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ Request::route()->getName() == 'leavetype.index' ? 'active' : '' }}"
                    id="leave-setting-tab" data-bs-toggle="pill" data-bs-target="#leave-setting"
                    type="button">{{ __('Leave Type') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Document Type')
        <li class="nav-item" role="presentation">
            <a href="{{ route('document.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ Request::route()->getName() == 'document.index' ? 'active' : '' }}"
                    id="document-setting-tab" data-bs-toggle="pill" data-bs-target="#document-setting"
                    type="button">{{ __('Document Type') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Payslip Type')
        <li class="nav-item" role="presentation">
            <a href="{{ route('paysliptype.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('paysliptype*') ? 'active' : '' }} " id="payslip-setting-tab"
                    data-bs-toggle="pill" data-bs-target="#payslip-setting"
                    type="button">{{ __('Payslip Type') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Allowance Option')
        <li class="nav-item" role="presentation">
            <a href="{{ route('allowanceoption.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('allowanceoption*') ? 'active' : '' }} "
                    id="allowance-setting-tab" data-bs-toggle="pill" data-bs-target="#allowance-setting"
                    type="button">{{ __('Allowance Option') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Loan Option')
        <li class="nav-item" role="presentation">
            <a href="{{ route('loanoption.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('loanoption*') ? 'active' : '' }} " id="loan-setting-tab"
                    data-bs-toggle="pill" data-bs-target="#loan-setting" type="button">{{ __('Loan Option') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Deduction Option')
        <li class="nav-item" role="presentation">
            <a href="{{ route('deductionoption.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('deductionoption*') ? 'active' : '' }} "
                    id="deduction-setting-tab" data-bs-toggle="pill" data-bs-target="#deduction-setting"
                    type="button">{{ __('Deduction Option') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Goal Type')
        <li class="nav-item" role="presentation">
            <a href="{{ route('goaltype.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('goaltype*') ? 'active' : '' }} " id="goal-setting-tab"
                    data-bs-toggle="pill" data-bs-target="#goal-setting" type="button">{{ __('Goal Type') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Training Type')
        <li class="nav-item" role="presentation">
            <a href="{{ route('trainingtype.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('trainingtype*') ? 'active' : '' }} " id="training-setting-tab"
                    data-bs-toggle="pill" data-bs-target="#training-setting"
                    type="button">{{ __('Training Type') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Award Type')
        <li class="nav-item" role="presentation">
            <a href="{{ route('awardtype.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('awardtype*') ? 'active' : '' }} " id="award-setting-tab"
                    data-bs-toggle="pill" data-bs-target="#award-setting" type="button">{{ __('Award Type') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Termination Type')
        <li class="nav-item" role="presentation">
            <a href="{{ route('terminationtype.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('terminationtype*') ? 'active' : '' }} "
                    id="termination-setting-tab" data-bs-toggle="pill" data-bs-target="#termination-setting"
                    type="button">{{ __('Termination Type') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Job Category')
        <li class="nav-item" role="presentation">
            <a href="{{ route('job-category.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('job-category*') ? 'active' : '' }} "
                    id="jobcategory-setting-tab" data-bs-toggle="pill" data-bs-target="#jobcategory-setting"
                    type="button">{{ __('Job Category') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Job Stage')
        <li class="nav-item" role="presentation">
            <a href="{{ route('job-stage.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('job-stage*') ? 'active' : '' }} " id="jobstage-setting-tab"
                    data-bs-toggle="pill" data-bs-target="#jobstage-setting"
                    type="button">{{ __('Job Stage') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Performance Type')
        <li class="nav-item" role="presentation">
            <a href="{{ route('performanceType.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('performanceType*') ? 'active' : '' }} "
                    id="performance-setting-tab" data-bs-toggle="pill" data-bs-target="#performance-setting"
                    type="button">{{ __('Performance Type') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Competencies')
        <li class="nav-item" role="presentation">
            <a href="{{ route('competencies.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('competencies*') ? 'active' : '' }} "
                    id="competencies-setting-tab" data-bs-toggle="pill" data-bs-target="#competencies-setting"
                    type="button">{{ __('Competencies') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Expense Type')
        <li class="nav-item" role="presentation">
            <a href="{{ route('expensetype.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('expensetype*') ? 'active' : '' }} "
                    id="expensetype-setting-tab" data-bs-toggle="pill" data-bs-target="#expensetype-setting"
                    type="button">{{ __('Expense Type') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Income Type')
        <li class="nav-item" role="presentation">
            <a href="{{ route('incometype.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('incometype*') ? 'active' : '' }} " id="incometype-setting-tab"
                    data-bs-toggle="pill" data-bs-target="#incometype-setting"
                    type="button">{{ __('Income Type') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Payment Type')
        <li class="nav-item" role="presentation">
            <a href="{{ route('paymenttype.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('paymenttype*') ? 'active' : '' }} "
                    id="paymenttype-setting-tab" data-bs-toggle="pill" data-bs-target="#paymenttype-setting"
                    type="button">{{ __('Payment Type') }}</button>
            </a>
        </li>
    @endcan

    @can('Manage Contract Type')
        <li class="nav-item" role="presentation">
            <a href="{{ route('contract_type.index') }}" class="list-group-item list-group-item-action border-0">
                <button class="nav-link {{ request()->is('contract_type*') ? 'active' : '' }} "
                    id="contract_type-setting-tab" data-bs-toggle="pill" data-bs-target="#contract_type-setting"
                    type="button">{{ __('Contract Type') }}</button>
            </a>
        </li>
    @endcan
</ul>

{{ Form::model($accountlist, ['route' => ['accountlist.update', $accountlist->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('account_name', __('Account Name'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::text('account_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Account Name')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('initial_balance', __('Initial Balance'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::number('initial_balance', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Initial Balance')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('account_number', __('Account Number'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::number('account_number', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Account Number')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('branch_code', __('Branch Code'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::text('branch_code', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Branch Code')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('bank_branch', __('Bank Branch'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::text('bank_branch', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Bank Branch')]) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}

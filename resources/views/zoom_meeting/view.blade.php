<div class="modal-body">
    <div class="table-responsive">
        <table class="table table-bordered ">
            <tbody>
                <tr>
                    <td class="text-dark fw-bold">{{ __('Name') }}</td>
                    <td style="display: table-cell;"> {{ !empty($ZoomMeeting->title) ? $ZoomMeeting->title : '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="text-dark fw-bold">{{ __('Meeting Id') }}</td>
                    <td style="display: table-cell;">
                        {{ !empty($ZoomMeeting->meeting_id) ? $ZoomMeeting->meeting_id : '-' }}</td>
                </tr>
                <tr>
                    <td class="text-dark fw-bold">{{ __('User') }}</td>
                    <td style="display: table-cell;">
                        {{ !empty($ZoomMeeting->getUserInfo) ? $ZoomMeeting->getUserInfo->name : '-' }}</td>
                </tr>
                <tr>
                    <td class="text-dark fw-bold">{{ __('Start Date') }}</td>
                    <td style="display: table-cell;">
                        {{ !empty($ZoomMeeting->start_date) ? \Auth::user()->dateFormat($ZoomMeeting->start_date) : '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="text-dark fw-bold">{{ __('Duration') }}</td>
                    <td style="display: table-cell;">
                        {{ !empty($ZoomMeeting->duration) ? $ZoomMeeting->duration : '-' }}</td>
                </tr>
                <tr>
                    <td class="text-dark fw-bold">{{ __('Start URl') }}</td>
                    <td style="display: table-cell;">
                        @if ($ZoomMeeting->created_by == \Auth::user()->id && $ZoomMeeting->checkDateTime())
                            <a href="{{ $ZoomMeeting->start_url }}" class="text-secondary">
                                <p class="mb-0"><b>{{ __('Start meeting') }}</b> <i class="ti ti-external-link"></i>
                                </p>
                            </a>
                        @elseif($ZoomMeeting->checkDateTime())
                            <a href="{{ $ZoomMeeting->join_url }}" class="text-secondary">
                                <p class="mb-0"><b>{{ __('Join meeting') }}</b> <i class="ti ti-external-link"></i>
                                </p>
                            </a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-dark fw-bold">{{ __('Status') }}</td>
                    <td style="display: table-cell;">
                        @if ($ZoomMeeting->checkDateTime())
                            @if ($ZoomMeeting->status == 'waiting')
                                <span class="badge bg-info p-2 px-3 ">{{ ucfirst($ZoomMeeting->status) }}</span>
                            @else
                                <span
                                    class="badge bg-success p-2 px-3 ">{{ ucfirst($ZoomMeeting->status) }}</span>
                            @endif
                        @else
                            <span class="badge bg-danger p-2 px-3 ">{{ __('End') }}</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@extends($activeTemplate . 'layouts.master')

@section('content')
    @php
        $kyc = getContent('kyc.content', true);
    @endphp

    <div class="notice">
    </div>

    @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
        <div class="alert alert--gradient mb-4" role="alert">
            <div class="d-flex justify-content-between">
                <h4 class="alert__title">@lang('KYC Documents Rejected')</h4>
            </div>
            <hr>

            <p class="alert__desc mb-0">{{ __(@$kyc->data_values->reject) }} <a href="javascript::void(0)" class="link-color" data-bs-toggle="modal" data-bs-target="#kycRejectionReason">@lang('Click here')</a> @lang('to show the reason').
                <a href="{{ route('user.kyc.form') }}">@lang('Click Here')</a> @lang('to Re-submit Documents').
                <a class="alert__link" href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a>
            </p>
        </div>
    @elseif(auth()->user()->kv == Status::KYC_UNVERIFIED)
        <div class="alert alert--gradient mb-4" role="alert">
            <h4 class="alert__title">@lang('KYC Verification Required')</h4>
            <hr>
            <p class="alert__desc mb-0">{{ __(@$kyc->data_values->required) }} <a class="alert__link" href="{{ route('user.kyc.form') }}">@lang('Click Here to Submit Documents')</a></p>
        </div>
    @elseif(auth()->user()->kv == Status::KYC_PENDING)
        <div class="alert alert--gradient mb-4" role="alert">
            <h4 class="alert__title">@lang('KYC Verification Pending')</h4>
            <hr>
            <p class="alert__desc mb-0">{{ __(@$kyc->data_values->pending) }} <a class="alert__link" href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></p>
        </div>
    @endif

    <div class="row gy-4 mb-4 justify-content-center">
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card dashboard-card--compact  skeleton">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--base">
                        <i class="las la-spinner"></i>
                    </span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.order.open') }}" class="dashboard-card__coin-name mb-0 ">
                            @lang('Open Order') </a>
                        <h6 class="dashboard-card__coin-title"> {{ getAmount($widget['open_order']) }} </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card dashboard-card--compact  skeleton">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--success">
                        <i class="las la-check-circle"></i>
                    </span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.order.completed') }}" class="dashboard-card__coin-name mb-0">
                            @lang('Completed Order') </a>
                        <h6 class="dashboard-card__coin-title"> {{ getAmount($widget['completed_order']) }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card dashboard-card--compact  skeleton">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--danger">
                        <i class="las la-times-circle"></i>
                    </span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.order.canceled') }}" class="dashboard-card__coin-name mb-0 ">
                            @lang('Canceled Order') </a>
                        <h6 class="dashboard-card__coin-title"> {{ getAmount($widget['canceled_order']) }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card dashboard-card--compact  skeleton">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--base">
                        <span class="icon-trade fs-20"></span>
                    </span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.trade.history') }}" class="dashboard-card__coin-name mb-0">@lang('Total Trade') </a>
                        <h6 class="dashboard-card__coin-title"> {{ getAmount($widget['total_trade']) }} </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 align-items-start">
        <div class="col-xl-6">
            <div class="transection h-100">
                <h5 class="transection__title skeleton"> @lang('Recent Order') </h5>
                @forelse ($recentOrders as $recentOrder)
                    <div class="transection__item skeleton">
                        <div class="d-flex align-items-center">
                            <div class="transection__date">
                                <h6 class="transection__date-number text-white">
                                    {{ showDateTime($recentOrder->created_at, 'd') }}
                                </h6>
                                <span class="transection__date-text">
                                    {{ __(strtoupper(showDateTime($recentOrder->created_at, 'M'))) }}
                                </span>
                            </div>
                            <div class="transection__content">
                                <h6 class="transection__content-title">
                                    @php echo $recentOrder->orderSideBadge; @endphp
                                </h6>
                                <p class="transection__content-desc">
                                    @lang('Placed an order in the ')
                                    {{ @$recentOrder->pair->symbol }} @lang('pair to')
                                    {{ __(strtolower(strip_tags($recentOrder->orderSideBadge))) }}
                                    {{ showAmount($recentOrder->amount, currencyFormat: false) }}
                                    {{ @$recentOrder->pair->coin->symbol }}
                                </p>
                            </div>
                        </div>
                        @php echo $recentOrder->statusBadge; @endphp
                    </div>
                @empty
                    <div class="transection__item justify-content-center p-5 skeleton">
                        <div class="empty-thumb text-center">
                            <img src="{{ asset('assets/images/extra_images/empty.png') }}" />
                            <p class="fs-14">@lang('No order found')</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="col-xl-6">
            <div class="transection h-100">
                <h5 class="transection__title skeleton"> @lang('Recent Transactions') </h5>
                @forelse ($recentTransactions as $recentTransaction)
                    <div class="transection__item skeleton">
                        <div class="d-flex align-items-center">
                            <div class="transection__date">
                                <h6 class="transection__date-number text-white">
                                    {{ showDateTime($recentTransaction->created_at, 'd') }}
                                </h6>
                                <span class="transection__date-text">
                                    {{ __(strtoupper(showDateTime($recentTransaction->created_at, 'M'))) }}
                                </span>
                            </div>
                            <div class="transection__content">
                                <h6 class="transection__content-title">
                                    {{ __(ucwords(keyToTitle($recentTransaction->remark))) }}
                                </h6>
                                <p class="transection__content-desc">
                                    {{ __($recentTransaction->details) }}
                                </p>
                            </div>
                        </div>
                        @if ($recentTransaction->trx_type == '+')
                            <span class="badge badge--success">
                                @lang('Plus')
                            </span>
                        @else
                            <span class="badge badge--danger">
                                @lang('Minus')
                            </span>
                        @endif

                    </div>
                @empty
                    <div class="transection__item justify-content-center p-5 skeleton">
                        <div class="empty-thumb text-center">
                            <img src="{{ asset('assets/images/extra_images/empty.png') }}" />
                            <p class="fs-14">@lang('No transactions found')</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
        <div class="modal custom--modal fade" id="kycRejectionReason">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">@lang('KYC Document Rejection Reason')</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ auth()->user()->kyc_rejection_reason }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--secondary btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

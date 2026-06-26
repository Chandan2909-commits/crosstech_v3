@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end align-items-center gy-4">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap-reverse align-items-center justify-content-end gap-3 mb-3">
                <form>
                    <div class="input-group input--group">
                        <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}"
                            placeholder="@lang('Search by transactions')">
                        <button class="input-group-text bg--gradient border-0 text-white">
                            <i class="las la-search"></i>
                        </button>
                    </div>
                </form>
                <a href="{{ route('user.withdraw') }}" class="btn btn--base text-nowrap" type="button">
                    <i class="las la-store-alt"></i>
                    @lang('Withdraw Now')
                </a>
            </div>

            <div class="dashboard-card">
                <table class="table {{ $withdraws->count() ? 'table--responsive--md' : 'table--empty' }}">
                    <thead>
                        <tr>
                            <th>@lang('Gateway | Transaction')</th>
                            <th class="text-center">@lang('Initiated')</th>
                            <th class="text-center">@lang('Amount')</th>
                            <th class="text-center">@lang('Conversion')</th>
                            <th class="text-center">@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($withdraws as $withdraw)
                            <tr>
                                <td>
                                    <div>
                                        <span class="fw-bold"><span class="text-primary">
                                                {{ __(@$withdraw->method->name) }}</span></span>
                                        <br>
                                        <small>{{ $withdraw->trx }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-center text-wrap">
                                        {{ showDateTime($withdraw->created_at) }} <br>
                                        {{ diffForHumans($withdraw->created_at) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-center text-wrap">
                                        {{ showAmount($withdraw->amount) }} - <span class="text-danger"
                                            title="@lang('charge')">{{ showAmount($withdraw->charge) }} </span>
                                        <br>
                                        <strong title="@lang('Amount after charge')">
                                            {{ showAmount($withdraw->amount - $withdraw->charge) }}
                                        </strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-center text-wrap">
                                        1 {{ __(gs('cur_text')) }} =
                                        {{ showAmount($withdraw->rate, currencyFormat: false) }}
                                        {{ __($withdraw->currency) }}
                                        <br>
                                        <strong>{{ showAmount($withdraw->final_amount, currencyFormat: false) }}
                                            {{ __($withdraw->currency) }}</strong>
                                    </div>
                                </td>
                                <td>
                                    @php echo $withdraw->statusBadge @endphp
                                </td>
                                <td>
                                    <button class="btn btn--sm btn--base detailBtn"
                                        data-user_data="{{ json_encode($withdraw->withdraw_information) }}"
                                        @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                        <i class="la la-desktop"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('withdraw history') @endphp
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($withdraws->hasPages())
                {{ $withdraws->links() }}
            @endif
        </div>
    </div>

    <div class="modal fade custom--modal" id="withdrawModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Withdraw Money')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.withdraw.money') }}" method="post">
                        @csrf
                        <input name="currency" type="hidden" value="{{ @$singleCurrency->symbol }}">
                        <div class="form-group">
                            <label class="form-label">@lang('Gateway')</label>
                            <select class="form--control form--select" name="method_code" required>
                                <option value="" selected disabled>@lang('Select Payment Gateway')</option>
                                @foreach ($withdrawMethods as $withdrawMethod)
                                    <option value="{{ $withdrawMethod->id }}" data-resource="{{ $withdrawMethod }}">
                                        {{ __($withdrawMethod->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">@lang('Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" value="{{ old('amount') }}"
                                    class="form-control form--control" required>
                                <span class="input-group-text bg--base border--base">{{ gs('cur_text') }}</span>
                            </div>
                        </div>
                        <div class="my-3 preview-details d-none">
                            <ul class="list-group text-center">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Limit')</span>
                                    <span><span class="min fw-bold">0</span> {{ __(gs('cur_text')) }} - <span
                                            class="max fw-bold">0</span> {{ __(gs('cur_text')) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Charge')</span>
                                    <span><span class="charge fw-bold">0</span> {{ __(gs('cur_text')) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Receivable')</span> <span><span class="receivable fw-bold"> 0</span>
                                        {{ __(gs('cur_text')) }} </span>
                                </li>
                                <li class="list-group-item d-none justify-content-between rate-element">

                                </li>
                                <li class="list-group-item d-none justify-content-between in-site-cur">
                                    <span>@lang('In') <span class="base-currency"></span></span>
                                    <strong class="final_amo">0</strong>
                                </li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <button class="deposit__button btn btn--base w-100" type="submit"> @lang('Submit') </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- APPROVE MODAL --}}
    <div id="detailModal" class="modal custom--modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData">

                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary btn--sm"
                        data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.withdrawNow').on('click', function() {
                let modal = $('#withdrawModal');
                modal.modal('show');
            });

            $('select[name=method_code]').change(function() {
                if (!$('select[name=method_code]').val()) {
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                var resource = $('select[name=method_code] option:selected').data('resource');
                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);
                var rate = parseFloat(resource.rate)
                var toFixedDigit = 2;
                $('.min').text(parseFloat(resource.min_limit).toFixed(2));
                $('.max').text(parseFloat(resource.max_limit).toFixed(2));
                var amount = parseFloat($('input[name=amount]').val());
                if (!amount) {
                    amount = 0;
                }
                if (amount <= 0) {
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                $('.preview-details').removeClass('d-none');

                var charge = parseFloat(fixed_charge + (amount * percent_charge / 100)).toFixed(2);
                $('.charge').text(charge);
                if (resource.currency != `{{ gs('cur_text') }}`) {
                    var rateElement =
                        `<span>@lang('Conversion Rate')</span> <span class="fw-bold">1 {{ __(gs('cur_text')) }} = <span class="rate">${rate}</span>  <span class="base-currency">${resource.currency}</span></span>`;
                    $('.rate-element').html(rateElement);
                    $('.rate-element').removeClass('d-none');
                    $('.in-site-cur').removeClass('d-none');
                    $('.rate-element').addClass('d-flex');
                    $('.in-site-cur').addClass('d-flex');
                } else {
                    $('.rate-element').html('')
                    $('.rate-element').addClass('d-none');
                    $('.in-site-cur').addClass('d-none');
                    $('.rate-element').removeClass('d-flex');
                    $('.in-site-cur').removeClass('d-flex');
                }
                var receivable = parseFloat((parseFloat(amount) - parseFloat(charge))).toFixed(2);
                $('.receivable').text(receivable);
                var final_amo = parseFloat(parseFloat(receivable) * rate).toFixed(toFixedDigit);
                $('.final_amo').text(final_amo);
                $('.base-currency').text(resource.currency);
                $('.method_currency').text(resource.currency);
                $('input[name=amount]').on('input');
            });
            $('input[name=amount]').on('input', function() {
                var data = $('select[name=method_code]').change();
                $('.amount').text(parseFloat($(this).val()).toFixed(2));
            });

            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if (element.type != 'file') {

                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    }
                });
                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);

                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

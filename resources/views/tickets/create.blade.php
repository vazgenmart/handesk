@extends('layouts.app')
@section('content')
    <div class="description comment">
        <div class="breadcrumb">
            <a href="{{ url()->previous() }}">{{ trans_choice('ticket.ticket', 2) }}</a>
        </div>
    </div>
    {{ Form::open(["url" => '/request_form',"enctype"=>"multipart/form-data"]) }}
    <div class="comment description actions">
        <table class="maxw600 no-padding">
            <tr>
                <td class="w20"><b> {{ __('ticket.requester') }}:</b></td>
            </tr>
            <tr>
                <td>{{ __('user.name')  }}:</td>
                <td class="w100"><input type="name" name="requester[name]" class="w100" required></td>
            </tr>
            <tr>
                <td>{{ __('user.email') }}:</td>
                <td class="w100"><input type="email" name="requester[email]" class="w100" required></td>
            </tr>
        </table>
    </div>
    <div class="comment description actions">
        <table class="maxw600 no-padding">
            <tr>
                <td class="w20"><b> {{ __('Form') }}:</b></td>
            </tr>
            <tr>
                <td>{{ __('First Name')  }}:</td>
                <td class="w100"><input type="text" name="first_name" class="w100" @if(old('first_name'))
                    value="{{ old('first_name') }}"
                                        @else
                                        value=""
                            @endif ></td>
            </tr>
            <tr>
                <td>{{ __('Last Name') }}:</td>
                <td class="w100"><input type="text" name="last_name" class="w100" @if(old('last_name'))
                    value="{{ old('last_name') }}"
                                        @else
                                        value=""
                            @endif ></td>
            </tr>
            <tr>
                <td>{{ __('Email') }}:</td>
                <td class="w100"><input type="email" name="email" class="w100" @if(old('email'))
                    value="{{ old('email') }}"
                                        @else
                                        value=""
                            @endif></td>
            </tr>
            <tr>
                <td>{{ __('Phone Number') }}:</td>
                <td class="w100"><input type="number" min="0" name="phone" class="w100" @if(old('phone'))
                    value="{{ old('phone') }}"
                                        @else
                                        value=""
                            @endif></td>
            </tr>
            <tr>
                <td>{{ __('Country of Residence') }}:</td>
                <td class="w100">
                    <select id="country" name="country" title="Country of Residence" aria-required="true"
                            class="input">
                        <option value="">Please select...</option>
                        <option value="0" {{ old('country') === '0' ? 'selected' : '' }} id="tfa_89" class="">Austria</option>
                        <option value="1" {{ old('country') == 1 ? 'selected' : '' }} id="tfa_59" class="">Belgium</option>
                        <option value="2" {{ old('country') == 2 ? 'selected' : '' }} id="tfa_60" class="">Bulgaria</option>
                        <option value="3" {{ old('country') == 3 ? 'selected' : '' }} id="tfa_61" class="">Croatia</option>
                        <option value="4" {{ old('country') == 4 ? 'selected' : '' }} id="tfa_62" class="">Republic of Cyprus</option>
                        <option value="5" {{ old('country') == 5 ? 'selected' : '' }} id="tfa_63" class="">Czech Republic</option>
                        <option value="6" {{ old('country') == 6 ? 'selected' : '' }} id="tfa_64" class="">Denmark</option>
                        <option value="7" {{ old('country') == 7 ? 'selected' : '' }} id="tfa_65" class="">Estonia</option>
                        <option value="8" {{ old('country') == 8 ? 'selected' : '' }} id="tfa_66" class="">Finland</option>
                        <option value="9" {{ old('country') == 9 ? 'selected' : '' }} id="tfa_67" class="">France</option>
                        <option value="10" {{ old('country') == 10 ? 'selected' : '' }} id="tfa_68" class="">Germany</option>
                        <option value="11" {{ old('country') == 11 ? 'selected' : '' }} id="tfa_69" class="">Greece</option>
                        <option value="12" {{ old('country') == 12 ? 'selected' : '' }} id="tfa_70" class="">Hungary</option>
                        <option value="13" {{ old('country') == 13 ? 'selected' : '' }} id="tfa_71" class="">Ireland</option>
                        <option value="14" {{ old('country') == 14 ? 'selected' : '' }} id="tfa_72" class="">Italy</option>
                        <option value="15" {{ old('country') == 15 ? 'selected' : '' }} id="tfa_73" class="">Latvia</option>
                        <option value="16" {{ old('country') == 16 ? 'selected' : '' }} id="tfa_74" class="">Lithuania</option>
                        <option value="17" {{ old('country') == 17 ? 'selected' : '' }} id="tfa_75" class="">Luxembourg</option>
                        <option value="18" {{ old('country') == 18 ? 'selected' : '' }} id="tfa_76" class="">Malta</option>
                        <option value="19" {{ old('country') == 19 ? 'selected' : '' }} id="tfa_77" class="">Netherlands</option>
                        <option value="20" {{ old('country') == 20 ? 'selected' : '' }} id="tfa_78" class="">Poland</option>
                        <option value="21" {{ old('country') == 21 ? 'selected' : '' }} id="tfa_79" class="">Portugal</option>
                        <option value="22" {{ old('country') == 22 ? 'selected' : '' }} id="tfa_80" class="">Romania</option>
                        <option value="23" {{ old('country') == 23 ? 'selected' : '' }} id="tfa_81" class="">Slovakia</option>
                        <option value="24" {{ old('country') == 24 ? 'selected' : '' }} id="tfa_82" class="">Slovenia</option>
                        <option value="25" {{ old('country') == 25 ? 'selected' : '' }} id="tfa_83" class="">Spain</option>
                        <option value="26" {{ old('country') == 26 ? 'selected' : '' }} id="tfa_84" class="">Sweden</option>
                        <option value="27" {{ old('country') == 27 ? 'selected' : '' }} id="tfa_85" class="">United Kingdom</option>
                        <option value="28" {{ old('country') == 28 ? 'selected' : '' }} id="tfa_86" class="">Other</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>{{ __('Address and House number') }}:</td>
                <td class="w100"><input type="text" name="address" class="w100" @if(old('address'))
                    value="{{ old('address') }}"
                                        @else
                                        value=""
                            @endif></td>
            </tr>
            <tr>
                <td>{{ __('ZIP-Code') }}:</td>
                <td class="w100"><input type="text" name="zip" class="w100" @if(old('zip'))
                    value="{{ old('zip') }}"
                                        @else
                                        value=""
                            @endif></td>
            </tr>
            <tr>
                <td>{{ __('City') }}:</td>
                <td class="w100"><input type="text" name="city" class="w100" @if(old('city'))
                    value="{{ old('city') }}"
                                        @else
                                        value=""
                            @endif></td>
            </tr>
            <tr>
                <td>{{ __('Proof of Identity') }}:</td>
                <td class="w100"><input type="file" name="prof_of_identity[]">
                </td>
            </tr>
            <tr>
                <td>{{ __('Address Verification Document') }}:</td>
                <td class="w100"><input type="file" name="prof_of_address[]">
                </td>
            </tr>
            <tr>
                <td>{{ __('Request Type') }}:</td>
                <td class="w100"><select id="request" name="request_type" title="Request Type" aria-required="true"
                                         class="input">
                        <option value="">Please select...</option>
                        <option value="0" {{ old('request_type') === '0' ? 'selected' : '' }} id="request1" class="">Right of access by the data subject</option>
                        <option value="1" {{ old('request_type') == 1 ? 'selected' : '' }} id="request2" class="">Right to rectification</option>
                        <option value="2" {{ old('request_type') == 2 ? 'selected' : '' }} id="request3" class="">Right to erasure (‘right to be forgotten’)
                        </option>
                        <option value="3" {{ old('request_type') == 3 ? 'selected' : '' }} id="request4" class="">Right to restriction of processing</option>
                        <option value="4" {{ old('request_type') == 4 ? 'selected' : '' }} id="request5" class="">Right to data portability</option>
                        <option value="5" {{ old('request_type') == 5 ? 'selected' : '' }} id="request6" class="">Right to object</option>
                        <option value="6" {{ old('request_type') == 6 ? 'selected' : '' }} id="request7" class="">Other comment or question</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>{{ __('Please describe your request') }}:</td>
                <td class="w100">   <textarea name="request_description" cols="62" rows="4"
                                              placeholder="Type Text Here">{{old('request_description') ? old('request_description') : '' }}</textarea>
                </td>
            </tr>
        </table>
    </div>

    <div class="comment new-comment">
        <table class="maxw600 no-padding">
            <tr>
                <td class="w20">{{ __('ticket.subject') }}:</td>
                <td><input name="title" class="w100" required @if(old('title'))
                    value="{{ old('title') }}"
                           @else
                           value=""
                            @endif/></td>
            </tr>
            <tr>
                <td>{{ trans_choice('ticket.tag', 2)}}:</td>
                <td><input name="tags" id="tags" @if(old('tags'))
                    value="{{ old('tags') }}"
                           @else
                           value=""
                            @endif/></td>
            </tr>
            <tr>
                <td>{{ __('ticket.comment')         }}:</td>
                <td><textarea name="body" required>{{old('body') ? old('body') : '' }}</textarea></td>
            </tr>
            @include('components.assignTeamField')
            <tr>
                <td>{{ __('ticket.status') }}:</td>
                <td>
                    {{ Form::select("status", [
                        App\Ticket::STATUS_NEW      => __("ticket.new"),
                        App\Ticket::STATUS_OPEN     => __("ticket.open"),
                        App\Ticket::STATUS_PENDING  => __("ticket.pending"),
                    ]) }}
                    <button class="uppercase ph3 ml1"> @icon(comment) {{ __('ticket.new') }}</button>
                </td>
            </tr>
        </table>
        {{ Form::close() }}
    </div>
@endsection


@section('scripts')
    @include('components.js.taggableInput', ["el" => "tags", "endpoint" => "tickets", "object" => null])
@endsection
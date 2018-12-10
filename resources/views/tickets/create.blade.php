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
                <td class="w100"><input type="text" name="first_name" class="w100"></td>
            </tr>
            <tr>
                <td>{{ __('Last Name') }}:</td>
                <td class="w100"><input type="text" name="last_name" class="w100"></td>
            </tr>
            <tr>
                <td>{{ __('Email') }}:</td>
                <td class="w100"><input type="email" name="email" class="w100"></td>
            </tr>
            <tr>
                <td>{{ __('Phone Number') }}:</td>
                <td class="w100"><input type="number" min="0" name="phone" class="w100"></td>
            </tr>
            <tr>
                <td>{{ __('Country of Residence') }}:</td>
                <td class="w100">
                    <select id="country" name="country" title="Country of Residence" aria-required="true"
                            class="input">
                        <option value="">Please select...</option>
                        <option value="0" id="tfa_89" class="">Austria</option>
                        <option value="1" id="tfa_59" class="">Belgium</option>
                        <option value="2" id="tfa_60" class="">Bulgaria</option>
                        <option value="3" id="tfa_61" class="">Croatia</option>
                        <option value="4" id="tfa_62" class="">Republic of Cyprus</option>
                        <option value="5" id="tfa_63" class="">Czech Republic</option>
                        <option value="6" id="tfa_64" class="">Denmark</option>
                        <option value="7" id="tfa_65" class="">Estonia</option>
                        <option value="8" id="tfa_66" class="">Finland</option>
                        <option value="9" id="tfa_67" class="">France</option>
                        <option value="10" id="tfa_68" class="">Germany</option>
                        <option value="11" id="tfa_69" class="">Greece</option>
                        <option value="12" id="tfa_70" class="">Hungary</option>
                        <option value="13" id="tfa_71" class="">Ireland</option>
                        <option value="14" id="tfa_72" class="">Italy</option>
                        <option value="15" id="tfa_73" class="">Latvia</option>
                        <option value="16" id="tfa_74" class="">Lithuania</option>
                        <option value="17" id="tfa_75" class="">Luxembourg</option>
                        <option value="18" id="tfa_76" class="">Malta</option>
                        <option value="19" id="tfa_77" class="">Netherlands</option>
                        <option value="20" id="tfa_78" class="">Poland</option>
                        <option value="21" id="tfa_79" class="">Portugal</option>
                        <option value="22" id="tfa_80" class="">Romania</option>
                        <option value="23" id="tfa_81" class="">Slovakia</option>
                        <option value="24" id="tfa_82" class="">Slovenia</option>
                        <option value="25" id="tfa_83" class="">Spain</option>
                        <option value="26" id="tfa_84" class="">Sweden</option>
                        <option value="27" id="tfa_85" class="">United Kingdom</option>
                        <option value="28" id="tfa_86" class="">Other</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>{{ __('Address and House number') }}:</td>
                <td class="w100"><input type="text" name="address" class="w100"></td>
            </tr>
            <tr>
                <td>{{ __('ZIP-Code') }}:</td>
                <td class="w100"><input type="number" name="zip" class="w100"></td>
            </tr>
            <tr>
                <td>{{ __('City') }}:</td>
                <td class="w100"><input type="text" name="city" class="w100"></td>
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
                        <option value="0" id="request1" class="">Right of access by the data subject</option>
                        <option value="1" id="request2" class="">Right to rectification</option>
                        <option value="2" id="request3" class="">Right to erasure (‘right to be forgotten’)
                        </option>
                        <option value="3" id="request4" class="">Right to restriction of processing</option>
                        <option value="4" id="request5" class="">Right to data portability</option>
                        <option value="5" id="request6" class="">Right to object</option>
                        <option value="6" id="request7" class="">Other comment or question</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>{{ __('Please describe your request') }}:</td>
                <td class="w100">   <textarea name="request_description" cols="62" rows="4"
                                              placeholder="Type Text Here"></textarea>
                </td>
            </tr>
        </table>
    </div>

    <div class="comment new-comment">
        <table class="maxw600 no-padding">
            <tr>
                <td class="w20">{{ __('ticket.subject') }}:</td>
                <td><input name="title" class="w100" required/></td>
            </tr>
            <tr>
                <td>{{ trans_choice('ticket.tag', 2)}}:</td>
                <td><input name="tags" id="tags"/></td>
            </tr>
            <tr>
                <td>{{ __('ticket.comment')         }}:</td>
                <td><textarea name="body" required></textarea></td>
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
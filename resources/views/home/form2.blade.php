@extends('layouts.form')
@section('content')
    <body class="body2">
    <div class="container big_container">
        <div class="content">
            <form action="/request_form" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="from_site" id="from_site">
                <div class="row first_row">
                    <div class="col-12">
                        <div class="logo"><img src="images/DGD-Logo.jpg" alt="logo"></div>
                        <div class="clearfix"></div>
                        @if ($errors->any())
                            <div style="color: red; font-weight: bold"> {{ implode('', $errors->all(':message')) }}</div>
                        @endif
                        <p class="title">Data Subjects' Rights Request Form</p>
                        <p class="text">
                            If you are a resident of the European Union or otherwise entitled please fill out this
                            request
                            form
                            if
                            you wish to exercise your right to access, rectification, erasure, restriction of
                            processing,
                            data
                            portability or to object. Please fill out this form as well if you have any other comment or
                            question to
                            our Data Protection Officer or about the personal data we may process about you. If you do
                            not
                            wish
                            to
                            complete this form, you may also submit a written request to our Data Protection Officer.
                        </p>
                    </div>
                </div>
                <div class="row form_rows">
                    <div class="col-md-5 col-xs-12">
                        <label for="name">First Name</label>
                        <input type="text" id="name" class="input" name="first_name">
                    </div>
                    <div class="col-md-5 col-xs-12">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" class="input" name="last_name">
                    </div>
                </div>
                <div class="row form_rows">
                    <div class="col-md-5 col-xs-12">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="input" name="email">
                    </div>
                    <div class="col-md-5 col-xs-122">
                        <label for="phone">Phone Number</label>
                        <input type="number" id="phone" class="input" name="phone">
                    </div>
                </div>
                <div class="row form_rows">
                    <div class="col-md-5 col-xs-12">
                        <label for="country">Country of Residence</label>
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
                    </div>
                    <div class="col-md-5 col-xs-12">
                        <label for="address">Address and House number</label>
                        <input type="text" id="address" class="input" name="address">
                    </div>
                </div>
                <div class="row form_rows">
                    <div class="col-md-5 col-xs-12">
                        <label for="zip">ZIP-Code</label>
                        <input type="number" id="zip" class="input" name="zip">
                    </div>
                    <div class="col-md-5 col-xs-12">
                        <label for="city">City</label>
                        <input type="text" id="city" class="input" name="city">
                    </div>
                </div>

                <div class="row identity">
                    <div class="col-12">
                        <p class="title">
                            Proof of Identity
                        </p>
                        <p class="text">
                            Please upload a photo or scanof a photo ID (e.g. driving license, national identity card,
                            passport)
                            or any other proof of identity.
                        </p>
                    </div>
                    <div class="choose_block input_fields_wrap2 col-md-5 col-xs-12">
                        <label for="identity">Proof of Identity</label>
                        <div id="identity" class="identity_form input_placeholder">Choose File</div>
                        <span class="choose">No file chosen</span>
                        <input type="file" id="identity_file" class="file_input" name="prof_of_identity[]">
                    </div>
                    <div class="col-12">
                        <a href="/" class="upload add_field_button2">Upload another document
                        </a>
                    </div>
                </div>
                <div class="row address">
                    <div class="col-12">
                        <p class="title">
                            Proof of Address
                        </p>
                        <p class="text">
                            In order for us to verify your address, please upload a photo or scan of a proof of address
                            (e.g.
                            driving license, national identity card, passport) or any other proof of address.
                        </p>
                    </div>
                    <div class="choose_block input_fields_wrap col-md-5 col-xs-12">
                        <label for="address_input">
                            Address Verification Document
                        </label>
                        <div id="address_input" class="address_input input_placeholder">Choose File</div>
                        <span class="choose">No file chosen</span>
                        <input type="file" id="address_file" name="prof_of_address[]" class="file_input">
                    </div>
                    <div class="col-12">
                        <a href="/" class="upload add_field_button">Upload another document
                        </a>
                    </div>
                </div>
                <div class="row request_col">
                    <div class="col-12">
                        <p class="title">
                            Request Type
                        </p>
                        <p class="text">
                            In order for us to verify your address, please upload a photo or scan of a proof of address
                            (e.g.
                            driving license, national identity card, passport) or any other proof of address.
                        </p>
                    </div>
                    <div class="col-md-5 col-xs-12">
                        <select id="request" name="request_type" title="Request Type" aria-required="true"
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
                    </div>
                </div>
                <div class="row request_col">
                    <div class="col-12">
                        <p class="title">
                            Please describe your request
                        </p>
                        <p class="text">
                            Please specify in detail and mention the processing activity to which your request
                            is related. The more details you provide, the better we are able to answer your request.

                        </p>
                    </div>
                    <div class="col-md-5 col-xs-12">
                <textarea name="request_description" id="request_text" cols="100" rows="10"
                          placeholder="Type Text Here"></textarea>
                    </div>
                </div>

                <div class="row request_col">
                    <div class="col-12">
                        <p class="title" style="font-size: 26px">
                            About the personal information you provide in this form:
                        </p>
                        <p class="text">
                            The information you provide with this request form will be processed solely for the purpose
                            of
                            verifying
                            your identity and residency address, to identify the information and to answer your request.
                            Your
                            personal information will be accessed by our Data Protection Officer and/or other staff
                            involved
                            in
                            the
                            process. Your proof of identity and address will be deleted once your request has been
                            answered.
                        </p>
                        <p class="title request_col" style="font-size: 26px">
                            How we will process your request:
                        </p>
                        <p class="text">
                            We will answer your request, or request additional information from you within 30 days. We
                            may
                            extend
                            this process for up to two months, in which case we will notify you of the extension within
                            a
                            month.
                        </p>
                        <p class="text">
                            The processing of this request is free of charge, but we reserve the right, as allowed under
                            Article
                            12(5) GDPR, to charge an administrative fee or refuse to act on the request, if the request
                            is
                            manifestly unfounded or excessive, in particular because of its repetitive character. Please
                            note
                            that
                            we may refuse to act, as allowed under Article 12 (2) GDPR, if we are not in the position to
                            identify
                            you as the data subject.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 submit_col">
                        <button class="submit_btn">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </body>
    <script>
        $(document).ready(function () {
            $(document).on('change', 'input[type="file"]', function (e) {
                var fileName = e.target.files[0].name;

                $(this).siblings(".choose, .input_placeholder").text(fileName);
            });

            function getParentUrl() {
                var isInIframe = (parent !== window),
                    parentUrl = null;

                if (isInIframe) {
                    parentUrl = document.referrer;
                }

                return parentUrl;
            }

            $('#from_site').val(getParentUrl());
        });
    </script>
@endsection
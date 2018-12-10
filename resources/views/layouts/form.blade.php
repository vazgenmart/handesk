<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Document</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
            integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
            integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
            crossorigin="anonymous"></script>
</head>

@yield('content')
<script>
    $("#identity").on("click", function () {
        $("#identity_file").click();
    });
    $("#address_input").on("click", function () {
        $("#address_file").click();
    });
    $(document).ready(function () {
        var max_fields = 10; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap"); //Fields wrapper
        var wrapper2 = $(".input_fields_wrap2"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID
        var add_button2 = $(".add_field_button2"); //Add button ID

        var x = 1; //initlal text box count
        var y = 1;
        $(add_button).click(function (e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper).append('<div class="choose_block  input_fields_wrap col-md-12 new"><label for= "address_input_' + x + ' ">Address Verification Document </label><div id="address_input_' + x + '" class="address_input input_placeholder">Choose File</div><span class="choose">File size maximum 5 MB</span><input type="file" id="address_file_' + x + '" name="prof_of_address[]" class="file_input"></div><a href="#" class="remove_field">Remove</a></div>')
                ; //add input box
                $("#address_input_" + x).on("click", function () {
                    $("#address_file_" + x).click();
                });
            }
        });
        $(add_button2).click(function (e) { //on add input button click
            e.preventDefault();
            if (y < max_fields) { //max input box allowed
                y++; //text box increment
                $(wrapper2).append('<div class="choose_block  input_fields_wrap2 col-md-12 new"><label for= "identity_' + y + ' ">Proof of Identity </label><div id="identity_' + y + '" class="identity_form input_placeholder">Choose File</div><span class="choose">File size maximum 5 MB</span><input type="file" id="identity_file_' + y + '" class="file_input" name="prof_of_identity[]"></div><a href="#" class="remove_field2">Remove</a></div>')
                ; //add input box
                $("#identity_" + y).on("click", function () {
                    $("#identity_file_" + y).click();
                });
            }
        });


        $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
            e.preventDefault();
            $(this).prev().remove();
            $(this).remove();
            x--;
        });
        $(wrapper2).on("click", ".remove_field2", function (e) { //user click on remove text
            e.preventDefault();
            $(this).prev().remove();
            $(this).remove();
            y--;
        })
    });
</script>

</html>
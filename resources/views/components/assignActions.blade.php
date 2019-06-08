<?php
$members = App\Team::membersByTeam();
$team_id = '';
$users = \App\User::all();
//var_dump($users);die;
?>

<div class="description actions comment">
    {{ Form::open(["url" => route("{$endpoint}.assign", $object)]) }}

    <table class="w50 no-padding">
        {{--        <tr>--}}
        {{--            <td class="small"> {{ trans_choice('ticket.tag',2) }}:</td>--}}
        {{--            <td colspan="2"><input id="tags" name="tags" value="{{$object->tagsString()}}"></td>--}}
        {{--        </tr>--}}
        @can("assignToTeam", $object)
            @include('components.assignTeamField', ["team" => $object->team])
        @endcan
        <tr>
            @can("assignToTeam", $object)
                <td>{{ __('ticket.assigned') }}:</td>
                <td>{{ Form::select('user_id', $members, $object->user_id, ['class' => 'w100', 'id' => 'user_id']) }}</td>
            @else
                @if ($object->team)
                    <td>{{ __('ticket.assigned') }}:</td>
                    <td>{{ Form::select('user_id', createSelectArray( $object->team->members, true), $object->user_id, ['class' => 'w100', 'id' => 'user_id']) }}</td>
                @endif
            @endcan
        </tr>
        <tr>
            <td class="text-right" colspan="2">
                <button class="uppercase ph4"> {{ __('ticket.assign') }}</button>
            </td>
        </tr>
    </table>
    {{ Form::close() }}
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $("select#team_members").change(function () {
            var team = $(this).children("option:selected").text();
            var team_text = team.replace(/ /g,"_");
            var lab =  $("select#user_id optgroup").each(function (e) {
                $(this).attr('label',$(this).attr('label').replace(/ /g,"_"))
            });

            var hide = $("select#user_id optgroup").addClass('hidden');

            var group = $("select#user_id optgroup[label=" + team_text + "]").removeClass('hidden');
            console.log(lab)
        })
    })
</script>
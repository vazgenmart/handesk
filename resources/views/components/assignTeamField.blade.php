@php if (! isset($team)) $team = new App\Team; @endphp
<tr>
    <td>{{ trans_choice('team.team',1) }}:</td>
    <td>
        @can("assignToTeam", new App\Ticket)
            {{ Form::select('team_id', createSelectArray( App\Team::all(), true), $team->id, ['class' => 'w100', 'id'=>'team_members']) }}
        @else
            {{ Form::select('team_id', createSelectArray( auth()->user()->teams, false), $team->id,  ['class' => 'w100', 'id'=>'team_members']) }}
        @endcan
    </td>
</tr>
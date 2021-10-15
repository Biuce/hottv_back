@isset($lists)
    <?php $i = 0; ?>
    @foreach($lists as $k => $v)
        <tr>
            <td>{{ $v['assort'][$k] ?? "" }}</td>
            <td>{{ $prices[$i++] ?? 0 }}</td>
            <td class="own">{{ $v['own'][$k] ?? 0 }}</td>
            @if($level_id  == 8)
                <td class="editable">
                    <input class="form-control agency" type="text" name="agency" value="" maxlength="8"
                           style="width: 100px;" id="defind" data-content-id="{{ $i }}" onkeyup="onlyNumber(this, 1)" >
                </td>
            @else
                <td>{{ $v['choice'][$k] ?? 0 }}</td>
            @endif

            @if($level_id  == 8)
                <td class="profit">0</td>
                @if(\Auth::guard('admin')->user()->level_id == 8)
                    <td class="choice" style="display: none;">{{ $v['choice'][$k] ?? 0 }}</td>
                @else
                    <td class="choice">{{ $v['choice'][$k] ?? 0 }}</td>
                @endif
            @else
                <td>{{ $v['diff'][$k] ?? 0 }}</td>
            @endif
        </tr>
    @endforeach
@endisset

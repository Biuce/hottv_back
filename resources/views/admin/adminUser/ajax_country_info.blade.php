@isset($lists)
    <?php $i = 0; ?>
    @foreach($lists as $k => $v)
        <tr>
            <td>{{ $v }}</td>
            <td class="cost">
                <input class="form-control" type="text" name="cost" value="" maxlength="8"
                       style="width: 100px;" onkeyup="onlyNumber(this, 1)">
            </td>
            <td class="own">
                <input class="form-control" type="text" name="own" value="{{ $data[3][$k] ?? 0 }}" maxlength="8"
                       style="width: 100px;" onkeyup="onlyNumber(this, 1)">
            </td>
            <td class="diamond">
                <input class="form-control" type="text" name="diamond" value="{{ $data[4][$k] ?? 0 }}" maxlength="8"
                       style="width: 100px;" onkeyup="onlyNumber(this, 1)">
            </td>
            <td class="medal">
                <input class="form-control" type="text" name="medal" value="{{ $data[5][$k] ?? 0 }}" maxlength="8"
                       style="width: 100px;" onkeyup="onlyNumber(this, 1)">
            </td>
            <td class="silver">
                <input class="form-control agency" type="text" name="silver" value="{{ $data[6][$k] ?? 0 }}" maxlength="8"
                       style="width: 100px;" onkeyup="onlyNumber(this, 1)">
            </td>
            <td class="copper">
                <input class="form-control agency" type="text" name="copper" value="{{ $data[7][$k] ?? 0 }}" maxlength="8"
                       style="width: 100px;" onkeyup="onlyNumber(this, 1)">
            </td>
            <td class="defined">
                <input class="form-control agency" type="text" name="defined" value="{{ $data[8][$k] ?? 0 }}" maxlength="8"
                       style="width: 100px;" onkeyup="onlyNumber(this, 1)">
            </td>
        </tr>
    @endforeach
@endisset

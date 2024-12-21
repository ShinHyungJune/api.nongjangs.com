
<html>
<table>
    <thead>

    <tr>
        <th>번호</th>
        <th>방문센터</th>
        <th>이름</th>
        <th>연락처</th>
        <th>입장일자</th>
        <th>퇴장일자</th>
    </tr>
    </thead>
    <tbody>

    @foreach($items as $item)
        <tr>
            <td>{{$item["index"]}}</td>
            <td>{{\App\Enums\CenterType::getLabel($item["center_type"])}}</td>
            <td>{{$item["name"]}}</td>
            <td>{{$item["contact"]}}</td>
            <td>{{$item["entered_at"] ? \Carbon\Carbon::make($item["entered_at"])->format("Y-m-d H:i") : "-"}}</td>
            <td>{{$item["leaved_at"] ? \Carbon\Carbon::make($item["leaved_at"])->format("Y-m-d H:i") : "-"}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</html>


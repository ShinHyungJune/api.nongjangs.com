
<html>
<table>
    <thead>

    <tr>
        <th>번호</th>
        <th>센터</th>
        <th>프로그램</th>
        <th>연락처</th>
        <th>수취인</th>
        <th>발송일자</th>
    </tr>
    </thead>
    <tbody>

    @foreach($items as $item)
        <tr>
            <td>{{$item["index"]}}</td>
            <td>{{$item["center"]}}</td>
            <td>{{$item["program"]}}</td>
            <td>{{$item["contact"]}}</td>
            <td>{{$item["name"]}}</td>
            <td>{{$item["pushed_at"] ? \Carbon\Carbon::make($item["pushed_at"])->format("Y-m-d H:i") : "-"}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</html>


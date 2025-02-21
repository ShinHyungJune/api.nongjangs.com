
<html>
<table>
    <thead>

    <tr>
        <th>번호</th>
        <th>방문센터</th>
        <th>이용프로그램</th>
        <th>이름</th>
        <th>연락처</th>
        <th>등록일자</th>
    </tr>
    </thead>
    <tbody>

    @foreach($items as $item)
        <tr>
            <td>{{$item["index"]}}</td>
            <td>{{$item["format_center"]}}</td>
            <td>{{$item["program"]}}</td>
            <td>{{$item["name"]}}</td>
            <td>{{$item["contact"]}}</td>
            <td>{{$item["created_at"] ? \Carbon\Carbon::make($item["created_at"])->format("Y-m-d H:i") : "-"}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</html>


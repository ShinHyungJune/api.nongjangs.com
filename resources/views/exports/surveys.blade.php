
<html>
<table>
    <thead>

    <tr>
        <th>번호</th>
        <th>방문센터</th>
        <th>이름</th>
        <th>연락처</th>

        <th>성별</th>
        <th>연령대</th>
        <th>거주지</th>
        <th>알게된 경로</th>
        <th>방문횟수</th>
        <th>참여한 프로그램</th>
        <th>가장 흥미로웠던 프로그램</th>
        <th>프로그램 만족도</th>
        <th>운영 만족도</th>
        <th>향후 디지털기기 사용 도움 만족도</th>
        <th>재방문여부</th>


        <th>등록일자</th>
    </tr>
    </thead>
    <tbody>

    @foreach($items as $item)
        <tr>
            <td>{{$item["index"]}}</td>
            <td>{{$item["format_center"]}}</td>
            <td>{{$item["name"]}}</td>
            <td>{{$item["contact"]}}</td>

            <td>{{$item["gender"]}}</td>
            <td>{{$item["age"]}}</td>
            <td>{{$item["location"]}}</td>
            <td>{{$item["way_to_come"]}}</td>
            <td>{{$item["count_visit"]}}</td>
            <td>{{$item["programs"]}}</td>
            <td>{{$item["most_like"]}}</td>
            <td>{{$item["program_satisfaction"]}}</td>
            <td>{{$item["operate_satisfaction"]}}</td>
            <td>{{$item["help_it_satisfaction"]}}</td>
            <td>{{$item["revisit"]}}</td>

            <td>{{$item["created_at"] ? \Carbon\Carbon::make($item["created_at"])->format("Y-m-d H:i") : "-"}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</html>


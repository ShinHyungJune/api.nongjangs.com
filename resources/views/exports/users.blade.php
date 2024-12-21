
<html>
<table>
    <thead>
    <tr>
        <th>번호</th>
        <th>이메일</th>
        <th>닉네임</th>
        <th>연락처</th>
        <th>이메일 수신동의</th>
        <th>문자 수신동의</th>

        <th>인스타그램</th>
        <th>네이버블로그</th>
        <th>유튜브</th>

        <th>등록일</th>
    </tr>
    </thead>
    <tbody>

    @foreach($items as $item)
        <tr>
            <td>{{$item["index"]}}</td>
            <td>{{$item["email"]}}</td>
            <td>{{$item["nickname"]}}</td>
            <td>{{$item["contact"]}}</td>
            <td>{{$item["agree_promotion_email"] ? 'Y' : 'N'}}</td>
            <td>{{$item["agree_promotion_sms"] ? 'Y' : 'N'}}</td>

            <td>{{$item["instagram"]}}</td>
            <td>{{$item["blog"]}}</td>
            <td>{{$item["youtube"]}}</td>

            <td>{{$item["created_at"] ? \Carbon\Carbon::make($item["created_at"])->format("Y-m-d H:i") : "-"}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</html>


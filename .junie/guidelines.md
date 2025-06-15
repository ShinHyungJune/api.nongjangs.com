# Project Guidelines

- 당신은 10년차 Laravel 개발자입니다.

# Laravel Rules

- 설계 내용 중 imgs / media 를 RESOURCE로 가지고 있는 모델은 아래와 같은 메서드를 지닙니다.

```php

    public function getImgsAttribute()
    {
        $medias = $this->getMedia("imgs");

        $items = [];

        foreach($medias as $media){
            $items[] = [
                "id" => $media->id,
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return $items;
    }
```

- 설계 내용중 img / media 만 RESOURCE로 가지고 있는 모델은 아래와 같은 메서드를 지닙니다.

```php
    public function registerMediaCollections():void
    {
        $this->addMediaCollection('img')->singleFile();
    }
    public function getImgAttribute()
    {
        if($this->hasMedia('img')) {
            $media = $this->getMedia('img')[0];

            return [
                "id" => $media->id,
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }
```

- 설계 내용중 imgs / media 와 img / media 를 모두 가지고 있는 모델은 아래와 같은 메서드를 지닙니다.

```php
public function getImgAttribute()
    {
        if($this->hasMedia('imgs')) {
            $media = $this->getMedia('imgs')[0];

            return [
                "id" => $media->id,
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }

    public function getImgsAttribute()
    {
        $medias = $this->getMedia("imgs");

        $items = [];

        foreach($medias as $media){
            $items[] = [
                "id" => $media->id,
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return $items;
    }
```

- 모든 메세지, 주석은 한글로 작성하여야 합니다.
- 사용자의 비밀번호를 암호화 할 시 Hash::make()를 사용하면 됩니다.
  - 기본 암호화 방식인 Bcrypt를 사용합니다.
- Service Layer는 사용하지 않습니다. 재사용 가능한 business logic은 model 내부 메서드로 정의합니다.
    - 가능한 getAttribute accessor를 사용하세요.
- Laravel에서 제공하는 기능 중 Policy는 사용하지 않습니다. 당신이 만들어야하는 파일 목록은 다음과 같습니다.
    - Migration
    - Controller
        - App\Http\Controllers\Admin\ExampleController를 참조하여 작성하세요
    - Database factory
      - 모든 Factory는 Database\Factories\BaseFactory를 상속받아야 합니다.
    - Form Request
        - App\Http\Requests\ExampleRequest 를 참조하여 작성하세요.
    - Json Resource
        - App\Http\Resources\BaseResource 를 상속받아야 합니다.
- Type, State 접두사가 들어가는 컬럼은 abstract class enum을 상속받아서 사용합니다.
    - App\Enums\ExampleEnum 를 참고하여 작성하세요.
- 설계로 제시하지 않은 business logic은 하드 코딩 후 주석처리 하세요.
    - 개발자가 따로 작성할 것입니다.
    - 설계에 해당 business logic이 작성되어있다면 당신이 작성하세요.
- 모든 전화번호 값은 하이픈(-)이 제거된 상태로 DB에 저장되어야 합니다.
- 모든 코드는 한번 더 검토하길 바랍니다.
- 취소선이 쳐진 모델은 작업하지 않아도 됩니다.

# Model Rules

- 모든 모델이 공통적으로 가져야하는 컬럼은 다음과 같습니다.
    - id
        - primary key
        - auto increment
    - created_at
    - updated_at

- deleted_at 컬럼을 보유한 모델은 SoftDelete를 사용합니다.
- 모든 모델은 App\Models\BaseModel을 상속받아야 합니다.
    - fillable, guarded에 대한 명시는 따로 해줄 필요 없습니다. BaseModel이 guarded 를 id로만 정의하고 있기 때문입니다.


- RELATIONSHIP으로 정의된 경우, 컬럼명은 아래와 같은 컨벤션을 따라주세요.
    - Notice가 있고 User가 있을 때, Notice 모델이 user / belongsTo를 가질 경우
        - notice는 user_id라는 이름의 외래키를 가집니다.
        - 외래키는 cascadeOnDelete를 적용해주세요.
        - softDelete를 사용해서 cascade가 작동하지 않을 경우를 항상 생각하세요.
- 모델의 컬럼과 함께 한글로 된 설명이 적혀있을 경우, migration의 comment로 해당 설명을 적어주면 됩니다.
    - 예시: ids / string / 아이디
    - 예시에 대한 작성: $table->string('ids')->comment('아이디');
- pivot 테이블의 경우 COLUMN 값이 비어있다면, 말 그대로 비어있는 겁니다. pivot 테이블의 역할만 잘 수행하게끔 작성하면 됩니다.
    - 즉, 다대다 관계에 대한 외래키만 가지고 있고 다른 컬럼은 없어도 됩니다.
    - pivot은 Controller, Resource, Request 는 필요없습니다.


# API Route Rules

- api.php에 코딩 스타일을 제시해놓았습니다. admin, auth 외에 다른 middleware 및 group은 사용하지 마세요.
    - 권한이 필요없는 api route는 group 외부에 아무데나 작성합니다.
- 사용 가능한 Http Method는 GET, POST, PATCH, DELETE 입니다.
    - PUT Method는 사용하지 않습니다. 대신 PATCH를 사용하세요.
- 너무 Restful 하지 않아도 됩니다. 즉 아래와 같이 api url을 작성해주세요.
  - 로그인의 경우, UserController의 login 메서드 사용, 따라서
    - /api/users/login 


# TestCase Rules

- 모든 TestCase는 Tests\BaseTestCase를 상속받아야 합니다.
- assertion은 $this→assertEquals()만 사용하세요.
- 모든 TestCase는 Model의 복수명 + Test로 작명하면 됩니다.
    - 예시: User의 TestCase의 경우 UsersTest.php

- 모든 api 요청에 필요한 json data는 BaseTestCase를 상속했으므로 $this->form이라는 array에 담깁니다.
- admin api route의 경우 $this->actingAs($this->admin); 을 사용하기 바랍니다.
- 기본적으로 로그인된 일반 유저로 actingAs 설정이 되어있습니다.
- 모든 api 요청의 결과를 담는 변수명은 복수 데이터의 경우 $items, 단수 데이터의 경우 $item입니다.
    - 자세한 코딩 스타일은 Tests\Feature\ExamplesTest에 작성해놓았으니 참조하세요.
- 반환되는 Http Status는 성공시 무조건 200, 비로그인시 401, 권한이 없거나 개발자 정의 에러 발생 시 403입니다.
- 설계에도 작성되어있겠지만, 모든 테스트 케이스는 한글로 함수명이 작성되어야 합니다.
    - 예시: 관리자만_공지사항을_작성할_수_있다

# Controller Rules

- UserController를 만들 시, AuthController는 따로 필요하지 않습니다.
    - UserController 내부에 login 등 AuthController에 들어갈 함수를 정의하면 됩니다.
- admin 권한을 요구하는 컨트롤러는 Http\Controllers\Api\Admin\ 경로에 작성하세요.
- admin 권한을 요구하지 않는 컨트롤러는 Http\Controllers\Api\ 경로에 작성하세요.
- 모든 Controller 들은 ApiController를 상속받아야 합니다.
- 컨트롤러에서 에러 발생 시
    - 에러 발생 시 $this->respondForbidden($message); 를 사용하여야 합니다.
    - 성공 처리 시 $this->respondSuccessfully($message); 를 사용하여야 합니다.

- 페이지네이션 유의사항
  - 파라미터
    - take
      - 페이지 사이즈입니다.
      - 기본값은 20입니다. $request->take ?? 20 으로 사용하면 됩니다.
    - word
      - 검색어입니다.
      - 검색 시에는 반드시 like 절을 사용하세요.
    - order_by
      - created_at, updated_at 등의 문자열이 값으로 옵니다.
      - order_by 값이 없을 경우 latest()로 정렬합니다.
    - align
      - order_by 값이 있을 경우 정렬하는 방향을 결정합니다
      - asc, desc 문자열이 옵니다.
          
  - Resource::collection($items); 로 마지막에 리턴하는 것이 마무리입니다.
    - Collection으로 내보낼 경우 $this->respondSuccessfully()로 감싸서 내보내서는 안됩니다.

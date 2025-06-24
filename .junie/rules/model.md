- models.md에서 imgs / media 를 RESOURCE로 가지고 있는 모델은 아래와 같은 메서드를 지닙니다.

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

- models.md에서 img / media 만 RESOURCE로 가지고 있는 모델은 아래와 같은 메서드를 지닙니다.

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

- models.md에서 imgs / media 와 img / media 를 모두 가지고 있는 모델은 아래와 같은 메서드를 지닙니다.

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
- 취소선이 쳐진 모델 또는 컬럼은 작업하지 않아야 합니다.
- boolean 타입인 데이터는 casts에서 int로 캐스팅되어야 합니다.
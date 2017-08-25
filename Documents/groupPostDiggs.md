# 圈子动态点赞列表

```
GET /groups/{group}/posts/{post}/likes
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 可选，默认值 15 ，获取条数 |
| after | Integer | 可选，上次获取到数据最后一条 ID，用于获取该 ID 之后的数据。 |

> 列表为倒序

#### Response

```
Status: 200 OK
```
```json5
[
    {
        "id": 512,
        "user_id": 20,
        "target_user": 226,
        "likeable_id": 100,
        "likeable_type": "group-posts",
        "created_at": "2017-08-24 11:58:32",
        "updated_at": "2017-08-24 11:58:32",
        "user": {
            "id": 20,
            "name": "炎亞綸的女孩",
            "location": "中国 四川省 成都市 武侯区 天府大道北段",
            "sex": 0,
            "bio": "啊哈哈哈哈久叔就久叔久叔侯珊侯珊侯珊何欢电话的何欢的侯珊侯珊的侯珊决定将顶焦度计绝对经典是一个人的时",
            "created_at": "2017-04-10 02:47:42",
            "updated_at": "2017-08-25 06:45:53",
            "avatar": "http://dev.zhibocloud.cn/api/v2/users/20/avatar",
            "bg": "http://dev.zhibocloud.cn/storage/user-bg/000/000/000/20.jpeg",
            "verified": {
                "type": "user",
                "icon": null
            },
            "extra": {
                "user_id": 20,
                "likes_count": 149,
                "comments_count": 238,
                "followers_count": 46,
                "followings_count": 25,
                "updated_at": "2017-08-25 06:16:21",
                "feeds_count": 86,
                "questions_count": 0,
                "answers_count": 0,
                "checkin_count": 2,
                "last_checkin_count": 2
            }
        }
    }
]
```
```
Status 404 Not Found
```

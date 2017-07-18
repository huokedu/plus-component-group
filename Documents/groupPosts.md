# 圈子动态列表

```
GET /groups/{group}/posts
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
        "id": 2,
        "title": "hehehsdfasdfasdf",
        "content": "hahahahha",
        "group_id": 1,
        "views": 3,
        "diggs": 0,
        "collections": 0,
        "comments": 6,
        "user_id": 2,
        "is_audit": 1,
        "created_at": "2017-07-18 04:17:19",
        "updated_at": "2017-07-18 06:59:55",
        "commentslist": [
            {
                "id": 7,
                "user_id": 2,
                "content": "55555555555555555555",
                "reply_to_user_id": 0,
                "created_at": "2017-07-18 06:59:55",
                "to_user_id": 2
            },
            {
                "id": 6,
                "user_id": 2,
                "content": "4444444444444444444444444444444",
                "reply_to_user_id": 0,
                "created_at": "2017-07-18 06:59:31",
                "to_user_id": 2
            },
            {
                "id": 4,
                "user_id": 2,
                "content": "sdfasdqerwerxxxxxxxasdfasdfasdf234234234234234a",
                "reply_to_user_id": 0,
                "created_at": "2017-07-18 06:48:29",
                "to_user_id": 2
            },
            {
                "id": 3,
                "user_id": 2,
                "content": "xxxxxxxasdfasdfasdf234234234234234",
                "reply_to_user_id": 0,
                "created_at": "2017-07-18 06:48:24",
                "to_user_id": 2
            },
            {
                "id": 2,
                "user_id": 2,
                "content": "xxxxxxxasdfasdfasdf",
                "reply_to_user_id": 0,
                "created_at": "2017-07-18 06:48:21",
                "to_user_id": 2
            }
        ],
        "images": [
            {
                "raw": "2",
                "size": "1200x800",
                "file_id": 3
            },
            {
                "raw": "2",
                "size": "600x1065",
                "file_id": 4
            }
        ]
    },
    {
        "id": 1,
        "title": "hhhhhhh",
        "content": "xxxxxxxxxxxxxxxxxxx",
        "group_id": 1,
        "views": 2,
        "diggs": 0,
        "collections": 0,
        "comments": 3,
        "user_id": 2,
        "is_audit": 1,
        "created_at": "2017-07-18 04:12:47",
        "updated_at": "2017-07-18 07:00:09",
        "commentslist": [
            {
                "id": 9,
                "user_id": 2,
                "content": "7777777777777777777",
                "reply_to_user_id": 0,
                "created_at": "2017-07-18 07:00:09",
                "to_user_id": 2
            },
            {
                "id": 8,
                "user_id": 2,
                "content": "6666666666666666666666666",
                "reply_to_user_id": 0,
                "created_at": "2017-07-18 07:00:05",
                "to_user_id": 2
            },
            {
                "id": 5,
                "user_id": 2,
                "content": "sdfasdqerwerxxxxxxxasdfasdfasdf234234234234234a",
                "reply_to_user_id": 0,
                "created_at": "2017-07-18 06:48:35",
                "to_user_id": 2
            }
        ],
        "images": []
    }
]
```
```
Status 404 Not Found
```

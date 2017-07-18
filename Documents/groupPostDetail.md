# 圈子详情

```
GET /groups/{group}/posts/{post}
```


#### Response

```
Status: 200 OK
```
```json5
{
    "id": 2,
    "title": "hehehsdfasdfasdf",
    "content": "hahahahha",
    "group_id": 1,
    "views": 2,
    "diggs": 0,
    "collections": 0,
    "comments": 0,
    "user_id": 2,
    "is_audit": 1,
    "created_at": "2017-07-18 04:17:19",
    "updated_at": "2017-07-18 04:17:19",
    "is_collection": 0,
    "is_digg": 0,
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
    ],
    "group": {
        "id": 1,
        "title": "heheh",
        "intro": "hahahahha",
        "is_audit": 1,
        "posts_count": 2,
        "memebers_count": 1,
        "created_at": "2017-07-18 03:51:40",
        "avatar": {
            "raw": "1",
            "size": "1920x1080",
            "file_id": 1
        },
        "cover": {
            "raw": "1",
            "size": "600x600",
            "file_id": 2
        },
        "members": [
            {
                "id": 1,
                "user_id": 2,
                "created_at": "2017-07-18 03:51:40"
            }
        ]
    }
}
```
```
Status 404 Not Found
```

> 动态评论在单独的接口中获取
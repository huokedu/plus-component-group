# 圈子详情

```
GET /groups/{group}
```


#### Response

```
Status: 200 OK
```
```json5
{
    {
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
        ],
        "managers": [
            {
                "group_id": 1,
                "user_id": 2,
                "founder": 1
            }
        ]
    }
}
```
```
status 404 Not Found
```

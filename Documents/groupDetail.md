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
    "message": "获取成功",
    "data": {
        "id": 1,
        "title": "group 1",
        "intro": "group1 description",
        "is_audit": 1,
        "posts_count": 0,
        "members_count": 1,
        "created_at": "2017-07-11 10:28:14",
        "avatar": {
            "raw": "1",
            "size": "1200x800",
            "file_id": 2
        },
        "cover": {
            "raw": "1",
            "size": "1200x775",
            "file_id": 1
        },
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

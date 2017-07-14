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
    "message": "获取成功",
    "data": {
        "id": 10,
        "title": "哈哈哈，我可以发帖了",
        "content": "来来来来来，快看我嘚瑟不??",
        "group_id": 2,
        "views": 3,
        "diggs": 1,
        "collections": 0,
        "comments": 3,
        "user_id": 2,
        "is_audit": 1,
        "created_at": "2017-07-13 07:39:16",
        "updated_at": "2017-07-14 09:55:12",
        "is_collection": 1,
        "is_digg": 0
    }
}
```

> 动态评论在单独的接口中获取
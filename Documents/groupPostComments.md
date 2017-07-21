# 圈子动态评论列表

```
GET /groups/{group}/posts/{post}/comments
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
{
    "message": [
        "获取成功"
    ],
    "data": [
        {
            "id": 14,
            "user_id": 2,
            "content": "xxxxxxx",
            "reply_to_user_id": 0,
            "created_at": "2017-07-14 10:13:53",
            "to_user_id": 2
        },
        {
            "id": 13,
            "user_id": 2,
            "content": "哎，来点孜然啊！",
            "reply_to_user_id": 0,
            "created_at": "2017-07-14 06:08:16",
            "to_user_id": 2
        }
    ]
}
```

```
status 404 Not Found
```

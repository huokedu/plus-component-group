# 创建圈子动态评论

```
POST /groups/{group}/posts/{post}/comment
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| content | String | images为空时必填, 动态内容 max:10000|
| reply_to_user_id | Integer | 选填, 在评论中回复某人{user_id} |

#### Request
```json5
    {
        "content": "评论内容",
        "reply_to_user_id": 0
    }
```

#### Response

```
Status: 201 OK
```
```json5
{
    "message": [
        "评论成功"
    ],
    "data": {
        "id": 14,
        "created_at": 1500027233,
        "user_id": 2,
        "reply_to_user_id": 0
    }
}
```

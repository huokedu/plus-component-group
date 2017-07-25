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
        "id": 6,
        "user_id": 2
    }
]
```
```
Status 404 Not Found
```

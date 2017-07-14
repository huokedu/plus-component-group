# 圈子成员

```
GET /groups/{group}/members
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 可选，默认值 15 ，获取条数 |
| before | Integer | 可选，上次获取到数据最后一条 ID，用于获取该 ID 之后的数据。 |

> 列表为正序

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
            "id": 18,
            "user_id": 2,
            "created_at": "2017-07-14 02:18:23"
        }
    ]
}
```

> 按加入先后顺序查找
# 创建圈子动态

```
POST /groups/{group}/posts
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| title | String | 必填, 动态标题, max: 30 |
| content | String | images为空时必填, 动态内容 max:10000|
| images | Array | content为空时必填 |
| group_post_mark | BigInteger | 必填 |

#### Request
```json5
    {
        "title": "圈子动态标题",
        "content": "圈子动态内容",
        "group_post_mark": 1500000000511,
        "images": [
            { "id" : 1 },
            { "id" : 2 }
        ]
    }
```

#### Response

```
Status: 201 OK
```
```json5
{
    "message": [
        "创建成功"
    ],
    "id": 11,
    "group_post_mark": 1500000000511
}
```

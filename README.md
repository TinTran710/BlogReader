# BlogReader
Package hỗ trợ đọc các thông tin từ 1 blog thuộc loại Blogspot/Wordpress

## Usage

Khởi tạo:

- Từ url. Nếu không tìm thấy blog thì trả về 1 exception BlogNotFoundException. Nếu tìm thấy thì trả về 1 trong 3 loại WpApiV1/WpApiV2/BlogSpot tương ứng
- Có thể khởi tạo kèm API Key mong muốn. Nếu không khai báo API key, package sẽ sử dụng API Key có sẵn.

```
$blog = BlogReader::fromUrl($url);
$blog = BlogReader::fromUrl($url, $api_key = null);
```

Đọc thông tin :

- Get info `$blog->getInfo()` lấy thông tin blog. Kết quả là array chứa các thông tin có thể lấy được từ api

- Get các bài viết

```

$blog->posts(array $fields = null, $page = null, $per_page = 20);// lấy các bài viết trang hiện tại hoặc trang tương ứng page truyền vào. Biến fields dạng array, tùy chọn field muốn lấy ra từ 1 bài viết.
$blog->next();// đọc trang tiếp theo, trả về true nếu thành công, false nếu hết trang. Để dọc nội dung thì gọi hàm trên với $page và $per_page mặc định
$blog->current_page(); // get page hiện tại, page_token nếu là blogspot

```

- Tìm kiếm

```

$blog->setKeyword($keyword);// cài đặt keyword, sau khi cài keyword, các posts, pages đọc như trên
$blog->resetKeyword();// xóa điều kiện keyword hiện tại


```

- Lấy ra labels có trên trang

```
$blog->labels($limit = 100);
```

- Thông tin blog :

```
[
    'name' => 'Tên blog',
    'description' => 'Mô tả hoặc slogan',
    'url' => 'Link đến trang chủ của blog',
    ... // có thể thêm 1 số thông tin tùy loại blog, các thông tin trên là bắt buộc có
]
```

- Các giá trị thành phần của $fields có thể có

```
[
    'id',
    'title',
    'created',
    'published',
    'updated',
    'content',
    'labels'
]
```

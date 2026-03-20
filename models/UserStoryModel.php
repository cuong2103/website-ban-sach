<?php
class UserStoryModel
{
    private const SESSION_KEY = 'user_stories';

    private $stories = [];

    public function __construct()
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = $this->defaultStories();
        }

        $this->stories = $_SESSION[self::SESSION_KEY];
    }

    public function getAll($search = '', $epic = '', $status = '', $limit = 10, $offset = 0, &$total = 0, $sortField = 'rank', $sortDir = 'asc')
    {
        $data = $this->stories;

        if ($search !== '') {
            $search = mb_strtolower($search, 'UTF-8');
            $data = array_filter($data, function ($item) use ($search) {
                return mb_stripos($item['id'], $search, 0, 'UTF-8') !== false
                    || mb_stripos($item['user_story'], $search, 0, 'UTF-8') !== false
                    || mb_stripos($item['epic'], $search, 0, 'UTF-8') !== false
                    || mb_stripos($item['as'], $search, 0, 'UTF-8') !== false
                    || mb_stripos($item['so_that'], $search, 0, 'UTF-8') !== false;
            });
        }

        if ($epic !== '') {
            $data = array_filter($data, fn($item) => $item['epic'] === $epic);
        }

        if ($status !== '') {
            $data = array_filter($data, fn($item) => $item['status'] === $status);
        }

        $allowedSortFields = ['id', 'epic', 'rank', 'priority', 'status'];
        if (!in_array($sortField, $allowedSortFields, true)) {
            $sortField = 'rank';
        }
        $sortDir = strtolower($sortDir) === 'desc' ? 'desc' : 'asc';

        usort($data, function ($a, $b) use ($sortField, $sortDir) {
            $valA = $a[$sortField] ?? '';
            $valB = $b[$sortField] ?? '';

            if (is_numeric($valA) && is_numeric($valB)) {
                $cmp = $valA <=> $valB;
            } else {
                $cmp = strcmp((string)$valA, (string)$valB);
            }

            return $sortDir === 'asc' ? $cmp : -$cmp;
        });

        $total = count($data);
        return array_slice($data, $offset, $limit);
    }

    public function deleteMany(array $ids)
    {
        $removed = false;
        foreach ($ids as $id) {
            foreach ($this->stories as $idx => $story) {
                if ($story['id'] === $id) {
                    array_splice($this->stories, $idx, 1);
                    $removed = true;
                    break;
                }
            }
        }
        if ($removed) {
            $this->save();
        }
        return $removed;
    }

    public function toggleStatusMany(array $ids)
    {
        $updated = false;
        foreach ($this->stories as $idx => $story) {
            if (in_array($story['id'], $ids, true)) {
                $this->stories[$idx]['status'] = $story['status'] === 'To do' ? 'Done' : 'To do';
                $updated = true;
            }
        }
        if ($updated) {
            $this->save();
        }
        return $updated;
    }

    public function getById($id)
    {
        foreach ($this->stories as $story) {
            if ($story['id'] === $id) {
                return $story;
            }
        }

        return null;
    }

    public function create($data)
    {
        if ($this->getById($data['id'])) {
            return false;
        }

        $this->stories[] = $data;
        $this->save();

        return true;
    }

    public function update($id, $data)
    {
        foreach ($this->stories as $idx => $story) {
            if ($story['id'] === $id) {
                $this->stories[$idx] = array_merge($story, $data);
                $this->save();
                return true;
            }
        }

        return false;
    }

    public function delete($id)
    {
        foreach ($this->stories as $idx => $story) {
            if ($story['id'] === $id) {
                array_splice($this->stories, $idx, 1);
                $this->save();
                return true;
            }
        }

        return false;
    }

    public function toggleStatus($id)
    {
        foreach ($this->stories as $idx => $story) {
            if ($story['id'] === $id) {
                $this->stories[$idx]['status'] = $story['status'] === 'To do' ? 'Done' : 'To do';
                $this->save();
                return true;
            }
        }

        return false;
    }

    private function save()
    {
        $_SESSION[self::SESSION_KEY] = $this->stories;
    }

    private function defaultStories()
    {
        return [
            ['id' => 'US01', 'epic' => 'Tài khoản', 'user_story' => 'Đăng ký', 'as' => 'Khách hàng', 'i_want' => 'đăng ký tài khoản', 'so_that' => 'có thể mua sách', 'priority' => 'Cao', 'story_point' => 3, 'rank' => 1, 'status' => 'To do'],
            ['id' => 'US02', 'epic' => 'Tài khoản', 'user_story' => 'Đăng nhập', 'as' => 'Khách hàng', 'i_want' => 'đăng nhập', 'so_that' => 'truy cập hệ thống', 'priority' => 'Cao', 'story_point' => 3, 'rank' => 2, 'status' => 'To do'],
            ['id' => 'US03', 'epic' => 'Sách', 'user_story' => 'Xem danh sách', 'as' => 'Khách hàng', 'i_want' => 'xem danh sách sách', 'so_that' => 'lựa chọn sản phẩm', 'priority' => 'Cao', 'story_point' => 3, 'rank' => 3, 'status' => 'To do'],
            ['id' => 'US04', 'epic' => 'Sách', 'user_story' => 'Xem chi tiết', 'as' => 'Khách hàng', 'i_want' => 'xem chi tiết sách', 'so_that' => 'hiểu sản phẩm', 'priority' => 'Cao', 'story_point' => 2, 'rank' => 4, 'status' => 'To do'],
            ['id' => 'US06', 'epic' => 'Sách', 'user_story' => 'Lọc sách', 'as' => 'Khách hàng', 'i_want' => 'lọc theo danh mục', 'so_that' => 'dễ chọn sách', 'priority' => 'Cao', 'story_point' => 2, 'rank' => 6, 'status' => 'To do'],
            ['id' => 'US08', 'epic' => 'Giỏ hàng', 'user_story' => 'Thêm vào giỏ', 'as' => 'Khách hàng', 'i_want' => 'thêm sách vào giỏ', 'so_that' => 'mua sau', 'priority' => 'Cao', 'story_point' => 2, 'rank' => 8, 'status' => 'To do'],
            ['id' => 'US09', 'epic' => 'Giỏ hàng', 'user_story' => 'Cập nhật số lượng', 'as' => 'Khách hàng', 'i_want' => 'thay đổi số lượng', 'so_that' => 'điều chỉnh đơn', 'priority' => 'Cao', 'story_point' => 2, 'rank' => 9, 'status' => 'To do'],
            ['id' => 'US10', 'epic' => 'Đặt hàng', 'user_story' => 'Tạo đơn', 'as' => 'Khách hàng', 'i_want' => 'đặt hàng', 'so_that' => 'mua sách', 'priority' => 'Cao', 'story_point' => 3, 'rank' => 10, 'status' => 'To do'],
        ];
    }
}

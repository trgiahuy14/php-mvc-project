    <table>
        <thead>
            <th>STT</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Ngày tạo</th>
        </thead>

        <tbody>
            <?php
            $dem = 1;
            foreach ($data as $item): ?>
                <tr>
                    <td><?php echo $dem++ ?></td>
                    <td><?php echo $item['fullname'] ?></td>
                    <td><?php echo $item['email'] ?></td>
                    <td><?php echo $item['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
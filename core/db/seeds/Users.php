<?php

use App\Models\File;
use App\Models\User;
use Phinx\Seed\AbstractSeed;
use Slim\Psr7\UploadedFile;
use Vesp\Services\Eloquent;

class Users extends AbstractSeed
{
    public const ROLE_ADMIN = 1;
    public const ROLE_USER = 2;

    public function getDependencies(): array
    {
        return ['UserRoles'];
    }

    public function run(): void
    {
        $db = (new Eloquent())->getDatabaseManager();
        $pdo = $db->getPdo();
        $stmt = $pdo->query(
            "SELECT `user`.*, `profile`.* FROM `modx_users`  `user`
            INNER JOIN `modx_user_attributes` `profile` ON `user`.`id` = `profile`.`internalKey`
            ORDER BY `user`.`id`"
        );
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            /** @var User $user */
            if (!$user = User::query()->where('remote_id', $row['internalKey'])->first()) {
                $user = new User();
            }

            // Save salt only for old passwords, native method doesn't need this
            $user->setRawAttributes([
                'password' => $row['password'],
                'salt' => $row['hash_class'] === 'hashing.modPBKDF2' ? $row['salt'] : null,
            ]);

            $user->remote_id = $row['internalKey'];
            $user->username = $row['username'];
            $user->fullname = $row['fullname'];
            $user->active = !empty($row['active']);
            $user->blocked = !empty($row['blocked']);
            $user->email = trim($row['email']);
            $user->phone = trim($row['phone']) ?: trim($row['mobilephone']);
            $user->gender = empty($row['gender']) ? null : (int)$row['gender'];
            $user->address = trim($row['address']) ?: null;
            $user->country = trim($row['country']) ?: null;
            $user->city = trim($row['city']) ?: null;
            $user->zip = trim($row['zip']) ?: null;
            $user->role_id = !empty($row['sudo']) ? self::ROLE_ADMIN : self::ROLE_USER;
            $user->created_at = date('Y-m-d H:i:s', $row['createdon'] ?: time());
            if (!empty($row['extended']) && $tmp = json_decode($row['extended'], true)) {
                $user->company = !empty($tmp['company']) ? trim($tmp['company']) : null;
                $user->lang = !empty($tmp['language']) ? trim($tmp['language']) : null;
            }
            $user->save();

            if ($row['photo']) {
                $filename = tempnam(getenv('UPLOAD_DIR'), 'img_');
                file_put_contents($filename, file_get_contents('https://i.pravatar.cc/500'));
                $data = new UploadedFile($filename, basename($filename), mime_content_type($filename));
                if (!$file = $user->file) {
                    $file = new File();
                }
                $file->uploadFile($data);
                $user->update(['file_id' => $file->id]);
                unlink($filename);
            }
        }
    }
}

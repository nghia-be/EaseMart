<?php

namespace App\Repository\Hi_FPT;

use App\Contract\Hi_FPT\SettingInterface;
use App\Models\Settings;
use App\Repository\RepositoriesAbstract;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use UnexpectedValueException;

class SettingRepository extends SettingInterface
{
    /**
     * {@inheritDoc}
     */
    public function forget($key): SettingInterface
    {
        parent::forget($key);

        // because the database store cannot store empty arrays, remove empty
        // arrays to keep data consistent before and after saving
        $segments = explode('.', $key);
        array_pop($segments);

        while ($segments) {
            $segment = implode('.', $segments);

            // non-empty array - exit out of the loop
            if ($this->get($segment)) {
                break;
            }

            // remove the empty array and move on to the next segment
            $this->forget($segment);
            array_pop($segments);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function write(array $data)
    {
        $keys = Settings::pluck('name');

        $insertData = Arr::dot($data);
        $updateData = [];
        $deleteKeys = [];

        foreach ($keys as $key) {
            if (isset($insertData[$key])) {
                $updateData[$key] = $insertData[$key];
            } else {
                $deleteKeys[] = $key;
            }
            unset($insertData[$key]);
        }

        foreach ($updateData as $key => $value) {
            Settings::where('name', $key)
                ->update(['value' => $value]);
        }

        if ($insertData) {
            Settings::insert($this->prepareInsertData($insertData));
        }

        if ($deleteKeys) {
            Settings::whereIn('name', $deleteKeys)->delete();
        }

    }

    /**
     * Transforms settings data into an array ready to be inserted into the
     * database. Call array_dot on a multidimensional array before passing it
     * into this method!
     *
     * @param array $data Call array_dot on a multidimensional array before passing it into this method!
     *
     * @return array
     */
    protected function prepareInsertData(array $data): array
    {
        $dbData = [];

        foreach ($data as $name => $value) {
            $dbData[] = compact('name', 'value');
        }
        return $dbData;
    }

    /**
     * {@inheritDoc}
     * @throws FileNotFoundException
     */
    protected function read(): array
    {
        return $this->parseReadData(Settings::get());
    }

    /**
     * {@inheritDoc}
     * @throws FileNotFoundException
     */
    protected function loadCronJob(): array
    {
        $data = Settings::get()->toArray();
        $check = array_filter($data, fn ($value) => substr($value['name'], 0, 13) == 'hi_admin_cron' ) ;
        return $this->parseReadData($check);
    }

    /**
     * Parse data coming from the database.
     *
     * @param Collection|array $data
     *
     * @return array
     */
    public function parseReadData($data): array
    {
        $results = [];

        foreach ($data as $row) {
            if (is_array($row)) {
                $key = $row['name'];
                $value = $row['value'];
            } elseif (is_object($row)) {
                $key = $row->name;
                $value = $row->value;
            } else {
                $msg = 'Expected array or object, got ' . gettype($row);

                throw new UnexpectedValueException($msg);
            }

            Arr::set($results, $key, $value);
        }
        return $results;
    }
}

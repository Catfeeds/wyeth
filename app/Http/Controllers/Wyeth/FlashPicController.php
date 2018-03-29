<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/9/30
 * Time: 9:23
 */

namespace App\Http\Controllers\Wyeth;

use App\Repositories\FlashPicRepository;

class FlashPicController extends WyethBaseController {

    protected $flashPicRepository;

    function __construct()
    {
        parent::__construct();
        $this->flashPicRepository = new FlashPicRepository();
    }

    public function get() {
        $data = $this->flashPicRepository->getFlashPics();
        return $data;
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Prediction;

class PredictionController extends Controller
{
    static $gridSize = 64;
    static $xCoordinates = ["a", "b", "c", "d", "e", "f", "g", "h"];
    static $yCoordinates = [1, 2, 3, 4, 5, 6, 7, 8];

    public static function convertToGridSystem($width, $height, $x, $y)
    {
        $gridX = self::$xCoordinates[count(self::$xCoordinates) -1];
        $gridY = self::$yCoordinates[count(self::$yCoordinates) -1];

        $width = $width - ($width % sqrt(self::$gridSize));
        $height = $height - ($height % sqrt(self::$gridSize));

        $sizeSquareSide = $width / sqrt(self::$gridSize);

        if ($x < $width) {
            $x = abs($x - ($x % $sizeSquareSide));
            $gridX = $x < $sizeSquareSide ? self::$xCoordinates[0] : self::$xCoordinates[$x / $sizeSquareSide];
        }

        if ($y < $height) {
            $y = abs($y - ($y % $sizeSquareSide));
            $gridY = $y < $sizeSquareSide ? self::$yCoordinates[0] : self::$yCoordinates[$y / $sizeSquareSide];
        }

        return $gridX . $gridY;
    }

    // return coordinates
    public static function convertFromGridSystem($width, $height, $gridKey, $random = false)
    {
        $postionX = 0;
        $positionY = 0;

        $gridX = $gridKey[0];
        $gridY = $gridKey[1];

        $sizeSquareSide = $width / sqrt(self::$gridSize);

        $gridPositionX = array_search($gridX, self::$xCoordinates);
        $gridPositionY = array_search($gridY, self::$yCoordinates);

        $positionX = (($gridPositionX + 1) * $sizeSquareSide);
        $positionY = (($gridPositionY + 1) * $sizeSquareSide) - ($sizeSquareSide / 2);

        if ($random) {
            $positionX = rand($positionX - $sizeSquareSide, $positionX);
            $positionY = rand($positionY - $sizeSquareSide, $positionY);
        } else {
            $positionX = $positionX - ($sizeSquareSide / 2);
            $positionY = $positionY - ($sizeSquareSide / 2);
        }

        return ['x' => $positionX, 'y' => $positionY];
    }

    public function store()
    {
        $this->validate(request(), [
            'postId' => 'required|numeric|min:1',
            'mouseX' => 'required|numeric|min:1',
            'mouseY' => 'required|numeric|min:1',
            'time' =>   'required|numeric|min:1',
            'width' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
        ]);
        $postId = request('postId');
        $userId = auth()->user()->id;
        $width = request('width');
        $height = request('height');
        $mouseX = request('mouseX');
        $mouseY = request('mouseY');

        // grid coordinate
        $grid = self::convertToGridSystem($width, $height, $mouseX, $mouseY);

        $predictionCount = Prediction::whereRaw('user_id=' . $userId . ' and post_id=' . $postId)->get()->count();
        $predictionCount = $predictionCount === 0 ? 1 : $predictionCount + 1;

        $post = Post::find(request('postId'));
        if ($predictionCount < 11) {
            $prediction = new Prediction([
                'user_id' => $userId,
                'post_id' => $post->id,
                'attractor' => $predictionCount,
                'time' => request('time'),
                'mouseX' => $mouseX,
                'mouseY' => $mouseY,
                'width' => $width,
                'height' => $height,
                'grid' => $grid,
            ]);
            $prediction->save();
        }

        response()->json(['success' => 'success'], 200);
    }
}

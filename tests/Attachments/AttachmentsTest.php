<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;

test('can fluently add attachments', function() {
    $this->fakeLogin();

    $fileContent = file_get_contents(__DIR__ . '/../stubs/potatoes.jpg');

    $file = UploadedFile::fake()->createWithContent('potatoes.jpg', $fileContent);

    Http::fake();

    MaximoQuery::withObjectStructure('test')
        ->withAttachments($file)
        ->create([]);

    $expected = [
        'urltype' => 'FILE',
        'documentdata' => base64_encode($fileContent),
        'doctype' => 'Attachments',
        'urlname' => 'potatoes.jpg',
    ];

    Http::assertSent(function ($request) use ($expected) {
        $data = $request->data();

        return key_exists('doclinks', $data) &&
            $data['doclinks'][0] === $expected;
    });
});

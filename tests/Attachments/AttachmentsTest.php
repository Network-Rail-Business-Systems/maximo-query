<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;

beforeEach(function () {
    $this->fakeLogin();

    $this->fileContent = file_get_contents(__DIR__ . '/../stubs/potatoes.jpg');
});

test('can add attachments from an uploaded file when creating resources', function() {
    $file = UploadedFile::fake()->createWithContent('potatoes.jpg', $this->fileContent);

    Http::fake();

    MaximoQuery::withObjectStructure('test')
        ->withUploadedFiles($file)
        ->create([]);

    assertExpectedPayload($this->fileContent);
});

test('can add attachments from an uploaded file when updating resources', function() {
    $file = UploadedFile::fake()->createWithContent('potatoes.jpg', $this->fileContent);

    Http::fake([
        '*/oslc/os/trim*' => Http::response(include(__DIR__ . '/../stubs/responses/single-record.php')),
        '*/oslc/os/mxperson*' => Http::response(include(__DIR__ . '/../stubs/responses/update-no-properties.php')),
    ]);

    MaximoQuery::withObjectStructure('trim')
        ->where('food', 'potato')
        ->withUploadedFiles($file)
        ->update([]);

    assertExpectedPayload($this->fileContent);
});


test('can add attachments from storage path when creating resources', function() {
    Storage::fake('attachments')
        ->put('potatoes.jpg', $this->fileContent);

    Http::fake();

    MaximoQuery::withObjectStructure('test')
        ->withAttachment('potatoes.jpg', 'potatoes.jpg', 'attachments')
        ->create([]);

    assertExpectedPayload($this->fileContent);
});

test('can add attachments from storage path when updating resources', function() {
    Storage::fake('attachments')
        ->put('potatoes.jpg', $this->fileContent);

    Http::fake([
        '*/oslc/os/trim*' => Http::response(include(__DIR__ . '/../stubs/responses/single-record.php')),
        '*/oslc/os/mxperson*' => Http::response(include(__DIR__ . '/../stubs/responses/update-no-properties.php')),
    ]);

    MaximoQuery::withObjectStructure('trim')
        ->where('food', 'potato')
        ->withAttachment('potatoes.jpg', 'potatoes.jpg', 'attachments')
        ->update([]);

    assertExpectedPayload($this->fileContent);
});


function assertExpectedPayload($fileContent)
{
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
}

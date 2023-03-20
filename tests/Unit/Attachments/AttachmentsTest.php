<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\Attachments;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\Data\MockResponses;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class AttachmentsTest extends TestCase
{
    protected string $fileContent;
    protected UploadedFile $file;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeLogin();
        
        $this->fileContent = file_get_contents($this->getTestFilePath('potatoes.jpg'));

        Http::fake([
            '*/oslc/os/trim*' => Http::response(MockResponses::singleRecord()),
            '*/oslc/os/mxperson*' => Http::response(MockResponses::updateNoProperties()),
            '*' => Http::response([]),
        ]);
        
        $this->file = UploadedFile::fake()->createWithContent('potatoes.jpg', $this->fileContent);
        Storage::fake('attachments')->put('potatoes.jpg', $this->fileContent);
    }
    
    public function testCanAddAttachmentsFromUploadedFileWhenCreatingResources(): void
    {
        MaximoQuery::withObjectStructure('test')
            ->withUploadedFiles($this->file)
            ->create([]);
    
        $this->assertExpectedPayload($this->fileContent);
    }
    
    public function testCanAddAttachmentsFromUploadedFileWhenUpdatingResources(): void
    {
        MaximoQuery::withObjectStructure('trim')
            ->where('food', 'potato')
            ->withUploadedFiles($this->file)
            ->update([]);
    
        $this->assertExpectedPayload($this->fileContent);
    }
    
    
    public function testCanAddAttachmentsFromStoragePathWhenCreatingResources(): void
    {
        MaximoQuery::withObjectStructure('test')
            ->withAttachment('potatoes.jpg', 'potatoes.jpg', 'attachments')
            ->create([]);
    
        $this->assertExpectedPayload($this->fileContent);
    }
    
    public function testCanAddAttachmentsFromStoragePathWhenUpdatingResources(): void
    {
        MaximoQuery::withObjectStructure('trim')
            ->where('food', 'potato')
            ->withAttachment('potatoes.jpg', 'potatoes.jpg', 'attachments')
            ->update([]);
    
        $this->assertExpectedPayload($this->fileContent);
    }
    
    protected function assertExpectedPayload($fileContent)
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
}

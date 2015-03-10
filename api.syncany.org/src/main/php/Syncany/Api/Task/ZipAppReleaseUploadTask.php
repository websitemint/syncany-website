<?php

namespace Syncany\Api\Task;

use Syncany\Api\Exception\Http\BadRequestHttpException;
use Syncany\Api\Util\FileUtil;
use Syncany\Api\Util\Log;
use Syncany\Api\Util\StringUtil;

class ZipAppReleaseUploadTask extends AppReleaseUploadTask
{
	public function execute()
	{
        Log::info(__CLASS__, __METHOD__, "Processing uploaded ZIP release file ...");

		$tempDirContext = "app/zip";
		$tempDir = FileUtil::createTempDir($tempDirContext);
		$tempFile = FileUtil::writeToTempFile($this->fileHandle, $tempDir, ".zip");

		$this->validateChecksum($tempFile);

		$targetFile = $this->moveFile($tempFile);
		$this->createLatestLink($targetFile);

		FileUtil::deleteTempDir($tempDir);
	}

	protected function getLatestLinkBasename()
	{
		$snapshotSuffix = ($this->snapshot) ? "-snapshot" : "";

		return StringUtil::replace("syncany-latest{snapshot}.zip", array(
			"snapshot" => $snapshotSuffix
		));
	}
}

<?php

namespace NITSAN\NsThemeNewage\Service;
use SimpleXMLElement;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use NITSAN\NsThemeNewage\Domain\Repository\ContentBlocksRepository;//in repository content block repo


class ContentBlockMigration
{
    private $contentBlocksRepository;

    private $connectionPool;

    public function __construct()
    {
        $this->contentBlocksRepository = GeneralUtility::makeInstance(ContentBlocksRepository::class);
        $this->connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
    }

    public function migrate(array $elements)
    {
        foreach ($elements as $ce) {
            $cType = 'nitsan_' . str_replace('_', '', $ce);
            $flexFormPath = GeneralUtility::getFileAbsFileName('EXT:ns_theme_newage/Configuration/FlexForms/');
            $originalXml = $this->scanAndParseXmlFiles($flexFormPath, $ce);
            $fields = $this->getFieldsFromXml($originalXml);
            $registeredContentElements = $this->contentBlocksRepository->getRegisteredContentElements($ce);
            if (!empty($registeredContentElements)) {
                foreach ($registeredContentElements as $element) {
                    if (isset($element['pi_flexform']) && $element['pi_flexform'] !== '') {
                        $pid = $element['pid'];
                        $uid = $element['uid'];
                        $langUid = $element['sys_language_uid'];
                        $xml = simplexml_load_string($element['pi_flexform']);
                        $parsed = [];

                        if (isset($xml->data->sheet)) {
                            foreach ($xml->data->sheet as $sheet) {
                                $fields = $sheet->language->field ?? null;
                                if ($fields) {
                                    $parsed = array_merge_recursive($parsed, $this->parseFields($fields));
                                }
                            }
                        }
                        $this->migrateFlexForm(
                            $uid,
                            $pid,
                            $cType,
                            $parsed,
                            $langUid
                        );
                    }
                }
            }
        }
    }

    private function parseFields($fields)
    {
        $result = [];

        foreach ($fields as $field) {
            $key = (string) $field['index'];

            // If it has a direct value (simple case)
            if (isset($field->value) && (string) $field->value['index'] === 'vDEF') {
                $result[$key] = html_entity_decode((string) $field->value);
            }

            // If it has nested <el> content
            if (isset($field->el)) {
                foreach ($field->el->field as $nestedField) {
                    $nestedKey = (string) $nestedField['index'];
                    $container = $nestedField->value['index'] == 'container' ? $nestedField->value->el->field : null;
                    try {
                        if ($container) {
                            // Ensure $result[$key] is an array before assigning to $result[$key][$nestedKey]
                            if (!isset($result[$key]) || !is_array($result[$key])) {
                                $result[$key] = [];
                            }

                            $result[$key][$nestedKey] = $this->parseFields($container);
                        }
                    } catch (\Exception $e) {
                        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($result, __FILE__ . ' ' . __LINE__);
                    }
                }
            }
        }

        return $result;
    }

    private function getFieldsFromXml(SimpleXMLElement $xml): array
    {
        $fields = [];
        if ($xml) {
            $elElements = $xml->xpath('//el')[0];
            foreach ($elElements as $elementName => $elementData) {

                $fieldType = (string)$elementData->config->type;
                if ($fieldType === '') {
                    $fieldType = 'Text';
                }

                if ($fieldType || ($elementData->section)) {

                    if ($elementData->el->container) {

                        $collectionData = [
                            'identifier' => $elementName,
                            'label' => (string)trim($elementData->title),
                            'type' => 'Collection',
                        ];

                        $containerElements = $elementData->xpath('el/container/el')[0];

                        foreach ($containerElements as $celementName => $celementData) {
                            $fieldType = (string)$celementData->config->type;
                            if ($fieldType === '') {
                                $fieldType = 'Text';
                            }
                            $fieldData = $this->extractFieldProperties($celementData, $fieldType, $celementName);
                            $collectionData['fields'][] = $fieldData;
                        }

                        $fields[] = $collectionData;
                    } else {

                        $fieldData = $this->extractFieldProperties($elementData, $fieldType, $elementName);
                        $fields[] = $fieldData;
                    }
                }
            }
        }
        return $fields;
    }

    private function handleFieldTypes(string $fieldType): array
    {
        return match ($fieldType) {
            'input' => ['type' => 'Text'],
            'text' => ['type' => 'Textarea'],
            'check' => ['type' => 'Checkbox'],
            'link' => ['type' => 'Link'],
            'select' => ['type' => 'Select'],
            default => [],
        };
    }

    private function scanAndParseXmlFiles(string $directory, string $targetFile): ?SimpleXMLElement
    {
        $files = scandir($directory);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'xml' && $file === $targetFile . '.xml') {
                $xmlContent = file_get_contents($directory . DIRECTORY_SEPARATOR . $file);
                return simplexml_load_string($xmlContent) ?: null;
            }
        }
        return null;
    }

    private function extractFieldProperties(SimpleXMLElement $elementData, string $fieldType, string $fieldName): array
    {
        $fieldData = [
            'identifier' => $fieldName,
            'label' => (string)trim($elementData->label ?? $elementData->title),
        ];

        if ($fieldType) {
            $fieldData = array_merge($fieldData, $this->handleFieldTypes($fieldType));
        }
        if (!empty($elementData->onChange)) {
            $fieldData['onChange'] = (string)$elementData->onChange;
        }
        if (!empty($elementData->itemsProcFunc)) {
            $fieldData['itemsProcFunc'] = (string)$elementData->itemsProcFunc;
        }
        if (!empty($elementData->displayCond)) {
            $condition = (string)$elementData->displayCond;
            $condition = str_replace('sDEF.', '', $condition);
            if ($condition !== 'AND' || $condition !== 'OR') {
                $fieldData['displayCond']['AND'] = [$condition];
            }
            $fieldData['size'] = (int)$elementData->config->size;
        }
        $renderType = (string)$elementData->config->renderType;

        if ($fieldType === 'number' || $fieldType === 'Number') {
            $fieldData['type'] = 'Number';
        }

        if ($fieldType === 'input' && $renderType) {

            switch ($renderType) {
                case 'inputLink':
                    $fieldData['type'] =  'Link';
                    break;

                case 'colorpicker':
                    $fieldData['type'] =  'Color';
                    break;

                case 'inputDateTime':
                    $fieldData['type'] =  'DateTime';
                    break;
            }
        }

        if ($fieldType === 'inline') {
            $foreigntable = (string)$elementData->config->foreign_table;
            if ($foreigntable === 'sys_file_reference') {
                $fieldData['type'] = 'File';
                $allowedTypes = '*';
                if (!empty($elementData->config->overrideChildTca->columns)) {
                    $allowedTypes =  (string)$elementData->config->overrideChildTca->columns->uid_local->config->appearance->elementBrowserAllowed;
                }
                if ($allowedTypes) {
                    $allowedTypes = GeneralUtility::trimExplode(',', $allowedTypes);
                    foreach ($allowedTypes as $type) {
                        $fieldData['allowed'][] = $type;
                    }
                }
            }
        }

        if ($fieldType === 'text') {
            $fieldData['rows'] = isset($elementData->config->rows) ? (int)(string)$elementData->config->rows : null;
            $fieldData['enableRichtext'] = !empty($elementData->config->enableRichtext)
                ? filter_var($elementData->config->enableRichtext, FILTER_VALIDATE_BOOLEAN)
                : false;
        }

        if ($fieldType === 'select') {
            $fieldData['renderType'] = (string)$renderType;
            $items = (array)$elementData->config->items;

            if ($items) {
                $items = $items['numIndex'];
                foreach ($items as $option) {
                    $fieldData['items'][] = [
                        'label' => (string)$option->numIndex[0],
                        'value' => (string)$option->numIndex[1],
                    ];
                }
            }
        }

        $validations = (string)$elementData->config->eval;

        if ($validations) {
            $fieldData = array_merge($fieldData, $this->handleValidations($validations));
        }

        if ($elementData->config->allowedTypes && $elementData->config->allowedTypes->numIndex) {

            $allowedTypes = (array)$elementData->config->allowedTypes->numIndex;

            if ($allowedTypes) {
                foreach ($allowedTypes as $type) {
                    if (is_string($type)) {
                        $fieldData['allowed'][] = $type;
                    }
                }
            }
        }

        $minitems = (int)$elementData->config->minitems;
        if ($minitems > 0) {
            $fieldData['minitems'] = $minitems;
        }

        $maxitems = (int)$elementData->config->maxitems;
        if ($maxitems > 0) {
            $fieldData['maxitems'] = $maxitems;
        }

        return $fieldData;
    }


    private function handleValidations(string $validations): array
    {
        $validationArray = GeneralUtility::trimExplode(',', $validations);
        $validationFields = [];

        foreach ($validationArray as $validation) {
            switch ($validation) {
                case 'required':
                    $validationFields['required'] = true;
                    break;
            }
        }

        return $validationFields;
    }


    public function migrateFlexForm(
        $uid,
        $pid,
        $cType,
        $parsed,
        $langUid
    ) {
        switch ($cType) {
            case 'nitsan_nscontact':
                $this->migrateContact($uid, $pid, $cType, $parsed, $langUid);
                break;
            case 'nitsan_nscta':
                $this->migrateCta($uid, $pid, $cType, $parsed, $langUid);
                break;
            case 'nitsan_nsfeature':
                $this->migrateFeature($uid, $pid, $cType, $parsed, $langUid);
                break;
            case 'nitsan_nsheader':
                $this->migrateHeader($uid, $pid, $cType, $parsed, $langUid);
                break;
            case 'nitsan_nsourapp':
                $this->migrateOurapp($uid, $pid, $cType, $parsed, $langUid);
                break;
            default:
                break;
        }
    }

    private function migrateContact($uid, $pid, $cType, $parsed, $langUid)
    {  
        
        $data = [
          
            'CType' => $cType,
            'beforeText' => $parsed['beforeText'] ?? '',
            'iconInText' => $parsed['iconInText'] ?? '',
            'afterText' => $parsed['afterText'] ?? '',
            'contact' => isset($parsed['contact']) ? count($parsed['contact']) : 0

           
        ];
 
        $this->updateTtContent($data, $uid, $pid);
         if (!empty($parsed['contact'])) {
            foreach (array_reverse($parsed['contact']) as $contact) {
                $randomString = StringUtility::getUniqueId('NEW');
                $data = [
                    'pid' => $pid,
                    'foreign_table_parent_uid' => $uid,
                    'SocialIcon' => $contact['socialIcon'] ?? '',
                    'socialLink' => $contact['socialLink'] ?? '',
                    'SocialBgColor' => $contact['SocialBgColor'] ?? '',
                ];
              
                $this->contentBlocksRepository->insertDataWithDataHandler($data, $randomString, 'contact');
            }
        }
    }

    private function migrateCta($uid, $pid, $cType, $parsed, $langUid)
    {
        $data = [
            'CType' => $cType,
            'headline' => $parsed['headline'] ?? '',
            'subHeadline' => $parsed['subHeadline'] ?? '',
            'btntxt' => $parsed['btntxt'] ?? '',
            'btnlink' => $parsed['btnlink'] ?? '',
        
        ];
        $this->updateTtContent($data, $uid, $pid);
    }

    private function migrateFeature($uid, $pid, $cType, $parsed, $langUid)
    {
        $data = [
            'CType' => $cType,
            'header' => $parsed['headline'] ?? '',
            'subheader' => $parsed['subheadline'] ?? '',
            'contact_feature' => isset($parsed['contact']) ? count($parsed['contact']) : 0
        ];
        $this->updateTtContent($data, $uid, $pid);
         if (!empty($parsed['contact'])) {
            foreach (array_reverse($parsed['contact']) as $contactFeature) {
                $randomString = StringUtility::getUniqueId('NEW');
                $data = [
                    'pid' => $pid,
                    'foreign_table_parent_uid' => $uid,
                    'socialIcon' => $contactFeature['socialIcon'] ?? '',
                    'title' => $contactFeature['title'] ?? '',
                    'text' => $contactFeature['text'] ?? '',
                ];

                $this->contentBlocksRepository->insertDataWithDataHandler($data, $randomString, 'contact_feature');
            }
        }
    }

    private function migrateHeader($uid, $pid, $cType, $parsed, $langUid)
    {
        $data = [
            'CType' => $cType,
            'text' => $parsed['text'] ?? '',
            // 'btntext' => $parsed['btnText'] ?? '',
            'link' => $parsed['link'] ?? '',
        ];
        $this->updateTtContent($data, $uid, $pid);
        
    }

    private function migrateOurapp($uid, $pid, $cType, $parsed, $langUid)
    {
        $data = [
            'CType' => $cType,
            'text' => $parsed['text'] ?? '',
        
        ];
        $this->updateTtContent($data, $uid, $pid);
    }

    private function updateTtContent($data, $uid, $pid)
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tt_content');
        $queryBuilder
            ->update('tt_content')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid))
            )
            ->andWhere(
                $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pid))
            );
        foreach ($data as $key => $val) {
            $queryBuilder->set($key, $val);
        }
        $queryBuilder->executeStatement();
    }

   
}

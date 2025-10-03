<?php

namespace Spatie\CraftMailcoach;

use Craft;
use craft\behaviors\EnvAttributeParserBehavior;
use craft\helpers\App;
use craft\mail\transportadapters\BaseTransportAdapter;
use Spatie\MailcoachMailer\MailcoachApiTransport;

final class Adapter extends BaseTransportAdapter
{
    public static function displayName(): string
    {
        return 'Mailcoach';
    }

    public ?string $apiToken = null;

    public ?string $host = null;

    public function attributeLabels(): array
    {
        return [
            'apiToken' => Craft::t('mailcoach', 'API Token'),
            'host' => Craft::t('mailcoach', 'Host'),
        ];
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['parser'] = [
            'class' => EnvAttributeParserBehavior::class,
            'attributes' => [
                'apiToken',
                'host',
            ],
        ];

        return $behaviors;
    }

    protected function defineRules(): array
    {
        return [
            [['apiToken', 'host'], 'required'],
        ];
    }

    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('mailcoach/settings', [
            'adapter' => $this,
        ]);
    }

    public function defineTransport(): MailcoachApiTransport
    {
        $transport = new MailcoachApiTransport(App::parseEnv($this->apiToken));
        $transport->setHost(App::parseEnv($this->host));

        return $transport;
    }
}

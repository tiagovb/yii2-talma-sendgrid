<?php

namespace talma\sendgrid;

use SendGrid\Content;
use SendGrid\Email;
use SendGrid\Mail;
use SendGrid\Personalization;
use yii\helpers\BaseArrayHelper;
use yii\helpers\Json;
use yii\mail\BaseMessage;

/**
 * Message implementation
 *
 * @package talma\sendgrid
 *
 * @property Mail $sendGridMessage
 */
class Message extends BaseMessage
{
    /**
     * @var Mail
     */
    private $_sendGridMessage;

    /**
     * @return Mail
     */
    public function getSendGridMessage()
    {
        if ($this->_sendGridMessage == null) {
            $this->_sendGridMessage = new Mail();
            if (empty($this->_sendGridMessage->getPersonalizations())) {
                $this->_sendGridMessage->addPersonalization(new Personalization());
            }
        }

        return $this->_sendGridMessage;
    }

    /**
     * @param string $templateId sendGrid template id
     * @param array [key => value] array for sendGrid substition
     *
     * @return $this
     */
    public function setSendGridSubstitution($templateId, array $templateSubstitution = [])
    {
        if (BaseArrayHelper::isAssociative($templateSubstitution)) {
            foreach ($templateSubstitution as $key => $value) {
                $this->getPersonalization()->addSubstitution($key, $value);
            }
        }

        $this->sendGridMessage->setTemplateId($templateId);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCharset()
    {
        // not available on sendgrid
    }

    /**
     * @inheritdoc
     */
    public function setCharset($charset)
    {
        // not available on sendgrid
    }

    /**
     * @inheritdoc
     */
    public function getFrom()
    {
        return $this->sendGridMessage->getFrom();
    }

    /**
     * @inheritdoc
     */
    public function setFrom($from)
    {
        if (is_array($from) && BaseArrayHelper::isAssociative($from)) {
            $mailAddress = key($from);
            $name = current($from);
            $email = new Email($name, $mailAddress);
        } else {
            $email = new Email(null, $from);
        }

        $this->sendGridMessage->setFrom($email);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getReplyTo()
    {
        return $this->sendGridMessage->getReplyTo();
    }

    /**
     * @inheritdoc
     */
    public function setReplyTo($replyTo)
    {
        $this->sendGridMessage->setReplyTo($replyTo);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTo()
    {
        return $this->getPersonalization()->getTos();
    }

    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     *
     * @inheritdoc
     */
    public function setTo($to)
    {
        if (is_array($to) && BaseArrayHelper::isAssociative($to)) {
            $address = key($to);
            $name = current($to);
            $email = new Email($name, $address);
        } else {
            $email = new Email(null, $to);
        }

        $this->getPersonalization()->addTo($email);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCc()
    {

        return $this->getPersonalization()->getCcs();
    }

    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     *
     * @inheritdoc
     */
    public function setCc($cc)
    {
        $this->getPersonalization()->addCc($cc);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBcc()
    {
        return $this->getPersonalization()->getBccs();
    }

    /**
     * @inheritdoc
     */
    public function setBcc($bcc)
    {
        $this->getPersonalization()->addBcc($bcc);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSubject()
    {
        return $this->sendGridMessage->getSubject();
    }

    /**
     * @inheritdoc
     */
    public function setSubject($subject)
    {
        $this->sendGridMessage->setSubject($subject);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTextBody($text)
    {
        $content = new Content("text/plain", $text);
        $this->sendGridMessage->addContent($content);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setHtmlBody($html)
    {
        if (!empty($html)) {
            $content = new Content("text/html", $html);
            $this->sendGridMessage->addContent($content);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function attach($fileName, array $options = [])
    {
        $this->sendGridMessage->addAttachment($fileName);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function attachContent($content, array $options = [])
    {
        // no available method for sendgrid
    }

    /**
     * @inheritdoc
     */
    public function embed($fileName, array $options = [])
    {
        // no available method for sendgrid
    }

    /**
     * @inheritdoc
     */
    public function embedContent($content, array $options = [])
    {
        // no available method for sendgrid
    }

    /**
     * Add categories to message
     *
     * @param array $categories Categories to be added
     *
     * @return $this
     */
    public function addCategories(array $categories = [])
    {
        foreach ($categories as $category) {
            if (!empty($this->sendGridMessage->categories) && in_array($category, $this->sendGridMessage->categories)) {
                continue;
            }

            $this->sendGridMessage->addCategory($category);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toString()
    {
        return Json::encode($this->sendGridMessage->jsonSerialize());
    }

    /**
     * @return Personalization
     */
    protected function getPersonalization()
    {
        return $this->sendGridMessage->getPersonalizations()[0];
    }
}

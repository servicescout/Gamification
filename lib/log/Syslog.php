<?php

namespace Log;

class Syslog
{
  const DEFAULT_LOG_IDENT = 'slimfw';
  const DEFAULT_LOG_PRIORITY = LOG_ERR;

  /**
   * Slim log interface implementation
   * @param type $message
   * @param int $level
   */
  public function write($message)
  {
    if ($message instanceof \Exception)
    {
      $this->syslog(
        $this->appendRequestInfo($message->getMessage()),
        $message->getFile(),
        $message->getLine(),
        $message->getTraceAsString()
      );
    }
    else
    {
      $this->syslog(
        $this->appendRequestInfo($message),
        null,
        null,
        $this->getTraceAsString()
      );
    }
  }

  private function appendRequestInfo($msg)
  {
    $app = \Slim\Slim::getInstance();
    $req = $app->request;

    if (!is_null($req))
    {
      $values = array_filter(array(
        $msg,
        $req->getUrl(),
        $req->getReferer() ? ('Referrer ' . $req->getReferer()) : '',
      ));

      $msg = implode(' | ', $values);
    }

    return $msg;
  }

  private function syslog($msg, $filename, $line, $trace)
  {
    if (isset($filename))
    {
      $msg .= " | File $filename";
    }

    if (isset($line))
    {
      $msg .= " | Line $line";
    }

    $syslogPriority = self::DEFAULT_LOG_PRIORITY;
    $logIdent = self::DEFAULT_LOG_IDENT;

    openlog("$logIdent (message)", LOG_NDELAY, LOG_USER);
    syslog($syslogPriority, $msg);
    closelog();

    if ($trace)
    {
      openlog("$logIdent (stack trace)", LOG_NDELAY, LOG_USER);

      foreach (explode("\n", $trace) as $logLine)
      {
        syslog($syslogPriority, $logLine);
      }

      closelog();
    }
  }

  private function getTraceAsString()
  {
    $backtrace = debug_backtrace();

    $trace = array_filter(array_map(function($traceStep)
    {
      $traceMsg = '';

      if (isset($traceStep['file']))
      {
        // skip entries in this file
        if ($traceStep['file'] === __FILE__)
        {
          return false;
        }

        $traceMsg .=  $traceStep['file'] . ': ';
      }

      if (isset($traceStep['class']))
      {
        $traceMsg .= 'function "' . $traceStep['class'];
      }

      if (isset($traceStep['function']))
      {
        $traceMsg .= isset($traceStep['class']) ? '::' : 'function "';
        $traceMsg .= $traceStep['function'] . '" ';
      }

      if (isset($traceStep['line']))
      {
        $traceMsg .= 'at line ' . $traceStep['line'];
      }

      return $traceMsg ?: 'unknown location';
    }, $backtrace));

    return implode("\n", $trace);
  }
}
<?php
/**
 * This class provides a symfony wrapper around the PEAR Date_Calc date library
 * in order to easily create an event calendar.
 *
 * All methods from the original Date_Calc class can be utilized via the getCalendar() method.
 * The documentation for these methods is located here: 
 *
 * http://pear.php.net/reference/Date-1.4/apidoc/Date-1.4/Date_Calc.html
 *
 * This class was originally part of sfEventCalendarPlugin by Ian Ricketson.
 * http://www.symfony-project.org/plugins/sfEventCalendarPlugin
 *
 * Since the class was simply a wrapper for another PEAR module, it has been included in its
 * entirety to avoid the need for a plugin dependency.
 * 
 * Copyright (c) 2004-2006 Fabien Potencier
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions
 * of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE. 
 */
class sfEventCalendar
{
  /**
   * Holds the Date_Calc object
   *
   * @var object
   */
  private $calendar = null;
  
  /**
   * Holds an array of events
   *
   * @var array
   */
  private $events = array();
  
  /**
   * Holds the calendar array
   *
   * @var array
   */
  private $event_calendar = array();
  
  /**
   * Initializes the event calendar
   * The $date parameter can accept any date string that can be utilized by strtotime
   *
   * @param string $type (day, week, month, year)
   * @param string $date
   */
  public function __construct($type, $date = null)
  {
    define('DATE_CALC_BEGIN_WEEKDAY', 0);

    if (!class_exists('Date_Calc'))
    {
      @include_once('Date/Calc.php');
    }
        
    if (!class_exists('Date_Calc'))
    {
      throw new sfException('Date_Calc is not installed. You must install the date library.');
    }
    
    $this->setCalendar(new Date_Calc());
    
    $this->setEventCalendar($type, strtotime($date));
  }
  
  /**
   * Convert a passed date to a standard date stamp
   *
   * @param string $date
   * @return string (formatted date)
   */
  private function toDateStamp($date)
  {
    if (!is_numeric($date))
    {
      $date = strtotime($date);
    }
    
    return date('Y-m-d', $date);
  }  
  
  /**
   * Set the Date_Calc object
   *
   * @param object $object
   */
  private function setCalendar($object)
  {
    $this->calendar = $object;
  }
  
  /**
   * Initialize the event calendar based on the type of calendar
   *
   * @param string $type (day, week, month, year)
   * @param int $time
   */
  private function setEventCalendar($type, $time)
  {
    switch (strtolower($type))
    {
      case "day":
        $this->event_calendar = array(0 => array(0 => array($this->toDateStamp($time) => array())));
        break;
              
      case "week":
        $this->event_calendar = $this->formatCalendarWeekArray($this->getCalendar()->getCalendarWeek(date('d', $time), date('m', $time), date('Y', $time), '%Y-%m-%d'));
        break;      
      
      case "month":
        $this->event_calendar = $this->formatCalendarMonthArray($this->getCalendar()->getCalendarMonth(date('m', $time), date('Y', $time), '%Y-%m-%d'));
        break;
        
      case "year":
        $this->event_calendar = $this->formatCalendarYearArray($this->getCalendar()->getCalendarYear(date('Y', $time), '%Y-%m-%d'));
        break;        

      default:
        throw new sfException('You must pass a valid calendar type: day, week, month, year');
        break;      
    }
  }

  /**
   * Format the week calendar from the Date_Calc format to a more usable format
   *
   * @param array $array
   * @return array
   */  
  private function formatCalendarWeekArray($array)
  {
    if (!empty($array))
    {
      foreach ($array as $key => $day)
      {
        $array[$day] = array();
        unset ($array[$key]);
      }
    }
    
    $week_array[0][0] = $array;
    
    return $week_array;
  }  
  
  /**
   * Format the month calendar from the Date_Calc format to a more usable format
   *
   * @param array $array
   * @return array
   */
  private function formatCalendarMonthArray($array)
  {
    if (!empty($array))
    {
      foreach ($array as $week => $days)
      {
        foreach ($days as $key => $value)
        {
          $array[$week][$value] = array();
          unset ($array[$week][$key]);
        }
      }
    }
    
    $month_array[0] = $array;
        
    return $month_array;
  }  
  
  /**
   * Format the year calendar from the Date_Calc format to a more usable format
   *
   * @param array $array
   * @return array
   */    
  private function formatCalendarYearArray($array)
  {
    if (!empty($array))
    {
      foreach ($array as $year => $weeks)
      {
        foreach ($weeks as $week => $days)
        {
          foreach ($days as $key => $day)
          {          
            $array[$year][$week][$day] = array();
            unset ($array[$year][$week][$key]);
          }
        }
      }
    }
        
    return $array;
  }  
  
  /**
   * Get the Date_Calc calendar object
   *
   * @return object
   */
  public function getCalendar()
  {
    return $this->calendar;
  }
  
  /**
   * Return an array off the specified calendar, optionally including events.
   *
   * @return array
   */
  public function getEventCalendar($events = true)
  {
    if ($events === true)
    {
      foreach ($this->event_calendar as $year => $weeks)
      {
        foreach ($weeks as $week => $days)
        {
          foreach ($days as $day => $events)
          {        
            $this->event_calendar[$year][$week][$day] = $this->getEvents($day);
          }
        }
      }
      
      while (count($this->event_calendar) == 1 AND isset($this->event_calendar[0]))
      {
        $this->event_calendar = $this->event_calendar[0];
      }
    }
    
    return $this->event_calendar;
  }
  
  /**
   * Return the number of events on any given date
   *
   * @param string $date
   * @return int
   */
  public function getEventCount($date)
  {
    $events = $this->getEvents($this->toDateStamp($date));
    
    return count($events);
  }
  
  /**
   * Return an array of events for the specified date, no calendar included
   *
   * @param string $date
   * @return array
   */
  public function getEvents($date)
  {
  	if (array_key_exists($date, $this->getAllEvents()))
  	{
      return $this->events[$this->toDateStamp($date)];
  	}
  	
  	return array();
  }
  
  /**
   * Return an array of all events, regardless of date.
   *
   * @return array
   */
  public function getAllEvents()
  {
    return $this->events;
  }
  
  /**
   * Add an event to the events array
   *
   * @param string $date
   * @param array $options
   */
  public function addEvent($date, $options = array())
  {
    $date = $this->toDateStamp($date);

    $event_count = $this->getEventCount($date);
    
    if (!empty($options))
    {
      foreach ($options as $key => $value)
      {
        $this->events[$date][$event_count][$key] = $value;
      }
    }
  }
}
<?php
/**
 * This file is part of GameQ.
 *
 * GameQ is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * GameQ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 */

/**
 * Provide an interface for easy manipulation of a server response
 *
 * @author         Aidan Lister <aidan@php.net>
 * @author         Tom Buskens <t.buskens@deviation.nl>
 * @version        $Revision: 1.4 $
 */
class GameQ_Buffer
{
    /**
     * The original data
     *
     * @var        string
     * @access     public
     */
    private $data;

    /**
     * The length of original data
     *
     * @var        string
     * @access     public
     */
    private $length;


    /**
     * Position of pointer
     *
     * @var        string
     * @access     public
     */
    private $position = 0;


    /**
     * Constructor
     *
     * @param   string|array    $response   The data
     */
    public function __construct($data)
    {
        $this->data   = $data;
        $this->length = strlen($data);
        $this->position = 0;
    }

    /**
     * Return all the data
     *
     * @return  string|array    The data
     */
    public function getData()
    { return $this->data; }

    /**
     * Return data currently in the buffer
     *
     * @return  string|array    The data currently in the buffer
     */
    public function getBuffer()
    { return substr($this->data, $this->position); }

    /**
     * Resets buffer
     */
    public function resetBuffer()
    {
        $this->data = "";
        $this->length = 0;
        $this->position = 0;
    }

    /**
     * Returns the number of bytes in the buffer
     *
     * @return  int  Length of the buffer
     */
    public function getLength()
    { return max($this->length - $this->position, 0); }

    /**
     * Read from the buffer
     *
     * @param   int             $length     Length of data to read
     * @return  string          The data read
     */
    public function read($length = 1)
    {
        if($length == 0) return '';
        if (($length + $this->position) > $this->length)
            throw new GameQ_ProtocolsException('Unable to read length={$length} from buffer.  Bad protocol format or return?');

        $string = substr($this->data, $this->position, $length);
        $this->position += $length;
        return $string;
    }

    //Alias
    public function getByte()
    { return $this->read(1); }

    /**
     * Read the last character from the buffer
     *
     * Unlike the other read functions, this function actually removes
     * the character from the buffer.
     *
     * @return  string          The data read
     */
    public function readLast()
    {
        $len           = strlen($this->data);
        $string        = $this->data{strlen($this->data) - 1};
        $this->data    = substr($this->data, 0, $len - 1);
        $this->length -= 1;
        return $string;
    }

    /**
     * Look at the buffer, but don't remove
     *
     * @param   int             $length     Length of data to read
     * @return  string          The data read
     */
    public function lookAhead($length = 1)
    { return substr($this->data, $this->position, $length); }

    /**
     * Skip forward in the buffer
     *
     * @param   int             $length     Length of data to skip
     * @return  void
     */
    public function skip($length = 1)
    { $this->position += $length; }

    /**
     * Jump to a specific position in the buffer,
     * will not jump past end of buffer
     *
     * @param   int   $index  Position to go to
     * @return  void
     */
    public function jumpto($index)
    { $this->position = min($index, $this->length - 1); }

    /**
     * Get the current pointer position
     *
     * @return  int  The current pointer position
     */
    public function getPosition()
    { return $this->position; }

    /**
     * Read from buffer until delimiter is reached
     *
     * If not found, return everything
     *
     * @param   string          $delim      Read until this character is reached
     * @return  string          The data read
     */
    public function readString($delim = "\x00")
    {
        // Get position of delimiter
        $len = strpos($this->data, $delim, min($this->position, $this->length));

        // If it is not found then return whole buffer
        if ($len === false) return $this->read(strlen($this->data) - $this->position);

        // Read the string and remove the delimiter
        $string = $this->read($len - $this->position);
        ++$this->position;
        return $string;
    }

    //Alias
    public function getString()
    { return $this->readString("\0"); }

    /**
     * Reads a pascal string from the buffer
     *
     * @paran   int    $offset        Number of bits to cut off the end
     * @param   bool   $read_offset   True if the data after the offset is
     *                                to be read
     * @return  string          The data read
     */
    public function readPascalString($offset = 0, $read_offset = false)
    {
        // Get the proper offset
        $len = $this->readInt8();
        $offset = max($len - $offset, 0);
        return ($read_offset ? $this->read($offset) : substr($this->read($len), 0, $offset)); // Read the data
    }

    /**
     * Read from buffer until any of the delimiters is reached
     *
     * If not found, return everything
     *
     * @param   array           $delims      Read until these characters are reached
     * @return  string          The data read
     */
    public function readStringMulti($delims, &$delimfound = null)
    {
        // Get position of delimiters
        $pos = array();
        foreach ($delims as $delim)
        { $p = strpos($this->data, $delim, min($this->position, $this->length)); if($p) $pos[] = $p; }

        // If none are found then return whole buffer
        if (empty($pos)) return $this->read(strlen($this->data) - $this->position);

        // Read the string and remove the delimiter
        sort($pos);
        $string = $this->read($pos[0] - $this->position);
        $delimfound = $this->read();
        return $string;
    }

    /**
     * Read a 32-bit unsigned integer
     */
    public function readInt32()
    {
        $int = unpack('Vint', $this->read(4));
        return $int['int'];
    }

    //Alias
    public function getUnsignedLong()
    { return $this->readInt32(); }

    /**
     * Read a 32-bit signed integer
     */
    public function readInt32Signed()
    {
        $int = unpack('lint', $this->read(4));
        return $int['int'];
    }

    //Alias
    public function getSignedLong()
    { return $this->readInt32Signed(); }

    //Alias
    public function getLong()
    { return $this->readInt32Signed(); }

    /**
     * Read a 16-bit unsigned integer
     */
    public function readInt16()
    {
        $int = unpack('vint', $this->read(2));
        return $int['int'];
    }

    //Alias
    public function GetShort( )
    { return $this->readInt16(); }

    /**
     * Read a 16-big signed integer
     */
    public function readInt16Signed()
    {
        $int = unpack('sint', $this->read(2));
        return $int['int'];
    }

    /**
     * Read an int8 from the buffer
     *
     * @return  int * The data read
     */
    public function readInt8()
    { return ord($this->read(1)); }

    /**
     * Read an float32 from the buffer
     *
     * @return  int * The data read
     */
    public function readFloat32()
    {
        $float = unpack('ffloat', $this->read(4));
        return $float['float'];
    }

    //Alias
    public function getFloat()
    { return $this->readFloat32(); }

    /**
     * Conversion to float
     *
     * @access     public
     * @param      string    $string   String to convert
     * @return     float     32 bit float
     */
    public function toFloat($string)
    {
        // Check length
        if (strlen($string) !== 4) return false;
        $float = unpack('ffloat', $string); // Convert
        return $float['float'];
    }

    /**
     * Conversion to integer
     *
     * @access     public
     * @param      string    $string   String to convert
     * @param      int       $bits     Number of bits
     * @return     int       Integer according to type
     */
    public function toInt($string, $bits = 8)
    {
        // Check length
        if (strlen($string) !== ($bits / 8))  return false;

        // Convert
        switch($bits) {
            case 8: $int = ord($string); break; // 8 bit unsigned
            case 16: $int = unpack('Sint', $string); $int = $int['int']; break; // 16 bit unsigned
            case 32: $int = unpack('Lint', $string); $int = $int['int']; break; // 32 bit unsigned
            default: $int = false; break;
        }

        return $int;
    }
}
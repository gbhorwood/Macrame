<?php
namespace Gbhorwood\Macrame;

/**
 * Handle command line arguments and options
 *
 */
class MacrameArgs
{
    /**
     * Parsed command line arguments
     * @var Array<Arg>
     * @access private
     */
    private Array $args;

    /**
     * The value(s) of the arguments at $argname
     * @var Arg
     * @access public
     */
    public Arg $argval;

    /**
     * Construct
     *
     * @param  String $argname
     */
    public function __construct(String $argname) {
        // build empty location for positional arguments
        $this->args['positional'] = new Arg();

        // all command line arguments minus sript name
        $args = array_slice($GLOBALS['argv'], 1);

        for ($i=0;$i<count($args);$i++) {
            switch (substr_count($args[$i], "-", 0, 2)) {
                // switches
                case 1:
                    foreach (str_split(ltrim($args[$i], "-")) as $a) {
                        if(!isset($this->args[$a])) {
                            $this->args[$a] = new Arg();
                        }
                        $this->args[$a]->count++;
                    }
                    break;

                // assignment args
                case 2:
                    $assignmentArgName = ltrim(preg_replace("/=.*/", '', $args[$i]), '-');
                    $assignmentArgVal = strpos($args[$i], '=') !== false ? substr($args[$i], strpos($args[$i], '=') + 1) : null;
                    if(!isset($this->args[$assignmentArgName])) {
                        $this->args[$assignmentArgName] = new Arg();
                    }
                    $this->args[$assignmentArgName]->values[] = $assignmentArgVal;
                    $this->args[$assignmentArgName]->count = count($this->args[$assignmentArgName]->values);
                    break;

                // positional args
                default:
                    $this->args['positional']->values[] = $args[$i];
                    $this->args['positional']->count = count($this->args['positional']->values);
            }
        }

        $this->argval = $this->args[$argname] ?? new Arg();
    }

    /**
     * Returns if there are values associated with $argname
     *
     * @return bool
     */
    public function exists():bool {
        return $this->argval->count > 0;
    }

    /**
     * Returns the number of values associated with the $argname
     * or, if the the $argval is a scalar, the value, which is the
     * count of switches.
     *
     * @return Int
     */
    public function count():Int {
        return $this->argval->count;
    }

    /**
     * Returns the first value associated with $argname
     * if any
     *
     * @return ?String
     */
    public function first():?String {
        return $this->argval->values[0] ?? null;
    }

    /**
     * Returns all the values associated with $argname
     * if any
     *
     * @return Array<String>
     */
    public function all():Array {
        return $this->argval->values;
    }
}

/**
 * Defines one command line argument
 *
 */
class Arg
{
    /**
     * The array of values for the arg.
     * ie. --f=1 --f=2 sets this as [1, 2]
     *
     * @var Array<String>
     */
    public Array $values = [];

    /**
     * The count of ocurrences of the argument
     *
     * @var Int
     */
    public Int $count = 0;

}

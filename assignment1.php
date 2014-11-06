<?php
/**
 * User: Steven Lambe
 * Date: 2014-10-06
 * Title: Assignment 
 * Made available for educational and reference use. All other uses must be approved by me, Steven Lambe. Please contract me at: firrae@gmail.com
 */
 
 /**
  * In this example you will see I use a function called var_dump() when debugging. 
  * This function allows you to see the contents of a variable including it's data type, value, and position if in an array.
  * I highly encourage you to read up about it further as it can save you a lot of time in debugging arrays and other variables.
  */

    /**
     * contains the possible words for the game
     */
    $possibleWords = array("magic hat", "horseman", "magician", "frosty", "frosty the snowman");

    /**
     * holds the letters that have been guessed
     */
    $guessedLetters = array();

    /**
     * holds the letters that have been missed during the check for what was missed
     */
    $missed = array();

    /**
     * holds the number of players in the game
     */
    $numOfPlayers = 2;

    /**
     * holds which players turn it is
     */
    $playerTurn = 1;

    /**
     * holds all the players and their information
     */
    $playerArray = array();

    /**
     * holds the guess we're on, if 4 it will be reset to 0 due to a vowel being picked
     */
    $guessCount = 0;

    /**
     * would dynamically create the players for use assuming they are playing against each other with separate wrong counts
     * These lines are better left ignored.
     */
     
    /*
    for($i = 0; $i < $numOfPlayers; $i++)
    {
        array_push($playerArray, array(
            "name" => "Player " . ($i + 1),
            "guessCount" => 0,
            "wrongAnswers" => 0
        ));
    }
    */
	
    //var_dump($playerArray[0]["name"]);    //This handy little function allows you to see everything that is in the variable passed through it.
	                                        //This will allow you to look at almost every variable type, and is most useful for debugging arrays.

    /**
     * @param $letterIndex  recieves the index of the letter chosen
     * @param $answer       recieves the full word
     * @param $gameBoard    recieves the game board as it is (passed by reference for modification)
     * @param $guesses      recieves the array of previous guesses (passed by reference for modification)
     *
     * @return bool   returns if the chosen letter has been used (false), if it not in the word (false), else it will return true as it is in the word.
     */
    function guess($letterIndex, $answer, &$gameBoard, &$guesses)
    {
        $alphabet = "b;c;d;f;g;h;j;k;l;m;n;p;q;r;s;t;v;w;x;y;z;"; //All consonants available to be guessed.
        $vowels = "a;e;i;o;u;"; //All vowels available to be guessed.

        global $guessCount; //Holds the number of guesses made.

        if($guessCount != 4) //Check it's not the 4th guess we'll know the letter is from the consonants list.
        {
            $position = strpos($alphabet, ";", ($letterIndex * 2) - 1) - 1; //Find the position of the letter in the consonants list.
            $letter = substr($alphabet, $position, 1); //Extract the letter form the list.
        }
        else //Else we know we're forcing a guess from the vowel list.
        {
            $position = strpos($vowels, ";", ($letterIndex * 2) - 1) - 1; //Find the position of the letter in the vowel list.
            $letter = substr($vowels, $position, 1); //Extract the letter form the list.
        }

        echo $letter . ".</p>\n\t"; //Show the letter that was chosen.

		//Check to see if the letter is in our list of guessed letters.
        if(!in_array($letter, $guesses))
        {
			//If it isn't then let's put it there now.
            array_push($guesses, $letter);

			//Debug code used to check variables and outcomes
            /*
            echo $letterIndex . "<br>";
            echo $letter . "<br>";
            var_dump($guesses);
            echo "<br>";
            */

            //echo "added " . $letter . " to used list.<br>"; //Another debug line.

			//Check to see if the letter is in the answer word. If it isn't it will return FALSE, if it is it will tell us it's first occurrence.
            if(strpos($answer, $letter) !== false)
            {
                //Loop through the word to look for matching letters.
                for($i = 0; $i < strlen($answer); $i++)
                {
                    //Check to see if the letter is one we need to replace.
                    if(substr($answer, $i, 1) == $letter)
                    {
                        //Place the letter in the correct slot.
                        $gameBoard = substr_replace($gameBoard, $letter, $i, 1);
                    }
                }

				//Once the loop is complete we show the player the game board.
                echo "<p>Game Board: " . $gameBoard . "</p><br>\n\t";
                return true; //We return true to tell the calling function that we have found and replaced the guessed letter in the game board.
            }
            else //If the letter is already guessed.
            {
				//Output that you are sorry they picked the same letter and show them the gameboard.
                echo "<p>Sorry but " . $letter . " is not in the word.</p>\n\t";
                echo "<p>Game Board: " . $gameBoard . "</p><br>\n\t";
                return false; //Return false to tell function that they did not find a new letter.
            }
        }
		
		//Catch all for the event that the player chooses a letter not in the word or inputs something else that is ineligible.
        echo "<p>Sorry but you've already guessed " . $letter . ".</p>\n\t";
        echo "<p>Game Board: " . $gameBoard . "</p><br>\n\t";
        return false; //Return false to tell function that they did not find a new 
    }
?>
	<!-- Begin HTML segment -->
	
    <!DOCTYPE html>
    <html>
        <head>
            <title>Assignment 1 - Steven Lambe</title>
        </head>
        <body>
            <h1>Welcome to Snowman!</h1>
            <p>This is a simulated game between 2 computer players choosing letters at random. I wish them both the best of luck as they don't seem very good at it.</p>
            <?php
				//Begin php block to play the game.
                for($x = 1; $x <= 5; $x++)
                {
                    echo "<h2>Game " . $x . "</h2>";

                    /**
                     * holds the number of wrong guesses
                     */
                    $wrong = 0;

                    /**
                     * holds the answer
                     */
                    $word = $possibleWords[rand(0, count($possibleWords) - 1)];

                    /**
                     * holds the game board with blanks, these are created using expressions
                     */
                    $board = preg_replace("(\p{L&})", "_", $word);

                    $guessedLetters = array(); //Initiates the guessed letters array.

                    $missed = array(); //Initiates the missed letters array.

                    $guessCount = 0; //Initiates the guess counter.

					//Game loop
                    while($word != $board && $wrong < 9)
                    {
                        echo "<p>Player " . $playerTurn . " has guessed "; //Announce who has guessed.

                        $guessCount++; //Increment the number of guesses used.

						//Check if it's the 4th guess and then force a guessed vowel.
                        if($guessCount == 4)
                        {
                            $random = rand(1, 5);
                        }
                        else //Else guess any letter in the alphabet.
                        {
                            $random = rand(1,21);
                        }

						//If the guess function returns false then increase the number of wrong guesses and show the snowman's new state.
                        if(!guess($random, $word, $board, $guessedLetters))
                        {
                            $wrong++;

                            if($wrong > 0)
                            {
                                echo "<img src = \"images/" . $wrong . ".jpg\" />"; //Builds the link for the snowman image. Takes the base nae and adds the number of wrong guesses to it to match my naming structure.
                            }
                        }

						//Check if the player who just played is the last player
						//and reset the number if they are.
                        if($playerTurn == $numOfPlayers)
                        {
                            $playerTurn = 1;
                        }
                        else //Else we will increase the players number by one for the next player.
                        {
                            $playerTurn++;
                        }

						//Reset the guess counter once it hits 4 and we've forced a vowel guess.
                        if($guessCount == 4)
                        {
                            $guessCount = 0;
                        }
                    }

					//Check to see if the game board now matches the answer.
					//If they match then we know the player has won.
                    if($word == $board)
                    {
                        echo "<p>Congratulations you have guessed the word " . $word . " in " . ($wrong + 2) . " tries.</p>\n\t";
                    }
                    else //Else we know they have lost and will proceed to show them the letters they missed.
                    {
                        echo "<p>Sorry but you were unable to guess the word. Here are the letters you missed:</p>\n\t";
                        echo "<p>";

						//Loop through the answer to find the missed letters to show them to the user.
                        for($i = 0; $i < strlen($word); $i++)
                        {
                            //echo "Step 1"; //Debug line.
							
							//Check to see if the letter at position i match.
                            if(substr($word, $i, 1) != substr($board, $i, 1) && substr($word, $i, 1) != " ") //Checking are they not similar AND they are not a blank space between words.
                            {
								//DEBUG BLOCK
                                //echo "Step 2";
                                //echo substr($word, $i, 1) . "<br>";
                                //echo "<br>";
                                //var_dump($missed);
                                //echo "<br>";
								
								
								//Check to make sure the letter at position i of the answer hasn't been guessed AND that the guessedLetters variable is NULL, 
								//this is to make sure we aren't adding a letter to the missed list that was actually guessed.
								
								//Check to make sure the letter at position i of the answer isn't already in the missed letters variable AND that the missed variable is NULL, 
								//this is to make sure we aren't adding the same letter twice..
                                if((!in_array(substr($word, $i, 1), $guessedLetters) || $guessedLetters == NULL) && (!in_array(substr($word, $i, 1), $missed) || $missed == NULL))
                                {
                                    //echo "Step 3";
									
									//Since we know it's not already in a tracked list we need to show it to the user.
                                    if($missed == NULL)
                                    {
                                        echo substr($word, $i, 1); //This one is used to output the first letter.
                                    }
                                    else
                                    {
                                        echo ", " . substr($word, $i, 1); //This else is used to show all other missed letters after the first with a comma in front of it for formatting.
                                    }

                                    //echo $i . "<br>"; //Debug line.

                                    array_push($missed, substr($word, $i, 1)); //Add the letter tot he missed array so we don't show it twice.
									
                                    //var_dump($missed); //Debug line.
                                }
                            }
                        }

                        echo "</p>\n\t";
                        echo "<p>The answer was: \"" . $word . "\"</p>"; //We also show them the completed answer.
                    }
                }
            ?>
        </body>
    </html>

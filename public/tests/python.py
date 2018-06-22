#underscores have been prepended to global variable names and some of the method names.
#hopefully, this will allow us to avoid any naming conflicts when this code is appended to a student's.


#these are the global variables that will be evaluated in the test function.
__returns = []
__in_strings = []
#__out_string = [None] #this was overwriting teacher's value, so I changed it to use an array. Don't know why this works.
__out_string = []

def split_type(arg):
    a = str(type(arg))
    if a[:5] == "<type":
        return a.split("<type '")[1].split("'>")[0]
    #return a.split("<class '")[1].split("'>")[0]
    return a[a.rfind(".")+1:a.rfind("'")]

#this is what all the functions used to validate a variable or a function eventually call.
#if a problem is encountered, it returns a string about the problem.
#if there is no problem, it returns true.
# -----------------------------------------------------------------------------------
def __UNIVERSAL_VALIDATOR(desired_var_name, desired_type_str, desired_return, *args):
    student_var = globals().get(desired_var_name, None)
    
    if desired_type_str == 'function':
        func_name = desired_var_name
        student_func = student_var
        arg_types = []

        for arg in args:
            #arg_type = str(type(arg)).split("<type '")[1].split("'>")[0]
            arg_type = split_type(arg)
            arg_types.append(arg_type)

        if student_var != None:
            #if str(type(student_func)).split("<type '")[1].split("'>")[0] == 'function':
            if split_type(student_func) == 'function':
                if (len(args) > 0):
                    try:
                        if (globals()[func_name](*args) != desired_return):
                            return "{0} does not return the proper value.".format(func_name)
                        else:
                            return True
                    except:
                        return "{0} must accept {1} arguments in the order: {2}.".format(func_name, str(len(args)), str(arg_types))
                else:
                    try:
                        if (globals()[func_name]() != desired_return):
                            return "{0} does not return the proper value.".format(func_name)
                        else:
                            return True
                    except:
                        return "{0} must accept no arguments.".format(func_name)
                    
            else:
                return "{0} must be a function.".format(func_name)
        else:
            return "You must create a function named {0}.".format(func_name)

    
    if student_var != None:
        #if str(type(student_var)).split("<type '")[1].split("'>")[0] == desired_type_str:
        if split_type(student_var) == desired_type_str:
            if (len(args) == 1):
                if (student_var == args[0]):
                    return True
                else:
                    return "{0} is not the correct value.".format(desired_var_name)
            elif (len(args) > 1):
                if (len(student_var) == len(args)):
                    arg_list = []
                    for arg in args:
                        arg_list.append(arg)
                    if (set(arg_list).intersection(set(student_var))) == set(arg_list):
                        return True
                    else:
                        return "{0} does not contain the correct values.".format(desired_var_name)
                else:
                    return "{0} should contain {1} items.".format(desired_var_name, len(args))
            else:
                return True
        else:
            return "{0} should be of type {1}.".format(desired_var_name, desired_type_str)
    else:
        return "You must declare a(n) {0} named {1}.".format(desired_type_str, desired_var_name)

# -----------------------------------------------------------------------------------



#these methods make it a little easier to call the universal validator for a func/variable.
# --------------------------------------------------------------------
def __VALIDATE_FUNC(func_name, desired_return, *args):
    return __UNIVERSAL_VALIDATOR(func_name, 'function', desired_return, *args)

def __VALIDATE_VAR(var_name, desired_type, *args):
    return __UNIVERSAL_VALIDATOR(var_name, desired_type, None, *args)

# --------------------------------------------------------------------



#these are the methods the professors will call in their test code
# --------------------------------------------------------------------
def test_val(var_name, var_val):
    #var_type = str(type(var_val)).split("<type '")[1].split("'>")[0]
    var_type = split_type(var_val)
    __returns.append(__VALIDATE_VAR(var_name, var_type, var_val))

def test_type(var_name, var_type):
    __ret = __VALIDATE_VAR(var_name, var_type)
    #if __ret != None:
    #    __returns.append(__ret)
    __returns.append(__ret)
    #__returns.append(__VALIDATE_VAR(var_name, var_type))

def test_func(func_name, desired_return, *params):
    __returns.append(__VALIDATE_FUNC(func_name, desired_return, *params))

def test_in(string):
    __in_strings.append(string)

def test_out(string):
    #__out_string[0] = string + "\n"
    __out_string.append(string + "\n")

def test_equal(a, b):
    if a <> b:
        __returns.append("{} not equal to {}".format(a, b))

# --------------------------------------------------------------------


#finally, this is the test function that will be called from the editor.js file
# --------------------------------------------------------------------
def __TEST(student_input, student_output):
    results = []
    
    #for thing in __returns:
    #    if thing != True:
    #        problems.append(thing)
    
    for x in __returns:
        if x != True:
            results.append([False, x])
        else:
            results.append([True, "The test is correct."])
        

    #missing_strings = ""
    #for x in __in_strings:
    #    if x not in student_input:
    #        missing_strings += str(x) + ", "
    #if missing_strings != "":
    #    problems.append("You must include the following string(s) in your code: " + missing_strings)
    
    for x in __in_strings:
        if x not in student_input:
            results.append([False, "The input is incorrect."])
        else:
            results.append([True, "The input is correct"])
    
    #if all(x in student_input for x in __in_strings) == False:
        #problems.append("You must include the following string(s) in your code: {0}.".format(str(__in_strings)))
        #problems.append("The code is incorrect.")
        #problems.append("test_in incorrect.")

    #if __out_string[0] != None:
    #    if student_output != __out_string[0]:
    #        problems.append([False, "The output was incorrect"])
    #    else:
    #        problems.append([True, "The output was correct"])
    for y in __out_string:
        if y != student_output:
            results.append([False, "The output is incorrect"])
        else:
            results.append([True, "The output is correct"])

    return results

def __TEST_EXAM(student_input, student_output):
    error_messages = []

    for thing in __returns:
        if thing != True:
            error_messages.append("Incorrect")

    for otherthing in __in_strings:
        if otherthing not in student_input:
            error_messages.append("Incorrect")

    if(__out_string[0] != None):
        if student_output != __out_string[0]:
            error_messages.append("Incorrect")

    return error_messages
    
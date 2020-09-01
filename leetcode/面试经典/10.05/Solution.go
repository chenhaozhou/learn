package main

func findString(words []string, s string) int {
    left := 0
    right := len(words)-1

    for left <= right {
        mid := (left + right) / 2
        tmp := mid

        for words[tmp] == "" && tmp < right {
            tmp ++
        }
        if(words[tmp] == ""){
            right = mid-1
            continue
        }
        mid = tmp

        if(words[mid] == s){
            return mid
        }else if(words[mid] < s){
            left = mid +1
        }else{
            right = mid - 1
        }
    }
    return -1
}